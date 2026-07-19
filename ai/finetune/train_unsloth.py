"""
QLoRA fine-tuning per UTC-BIM con Unsloth.
Modello base consigliato: mistralai/Mistral-7B-Instruct-v0.3

Requisiti hardware minimi:
  - GPU NVIDIA con >= 8 GB VRAM (RTX 3070, RTX 4060, A10G, ecc.)
  - 16 GB RAM di sistema
  - 30 GB di spazio su disco

Uso:
  pip install -r requirements.txt
  python train_unsloth.py
"""

from unsloth import FastLanguageModel
from datasets import load_dataset
from trl import SFTTrainer
from transformers import TrainingArguments
import torch
import os

# ---------------------------------------------------------------------------
# Configurazione
# ---------------------------------------------------------------------------

BASE_MODEL     = "mistralai/Mistral-7B-Instruct-v0.3"
OUTPUT_DIR     = "./utcbim-qlora"
DATASET_FILE   = "../dataset/training_data.jsonl"
MAX_SEQ_LENGTH = 2048
LORA_RANK      = 16

# ---------------------------------------------------------------------------
# Caricamento modello con QLoRA 4-bit
# ---------------------------------------------------------------------------

model, tokenizer = FastLanguageModel.from_pretrained(
    model_name   = BASE_MODEL,
    max_seq_length = MAX_SEQ_LENGTH,
    dtype        = None,    # auto: bfloat16 su Ampere+, float16 altrove
    load_in_4bit = True,
)

model = FastLanguageModel.get_peft_model(
    model,
    r              = LORA_RANK,
    target_modules = ["q_proj", "k_proj", "v_proj", "o_proj",
                      "gate_proj", "up_proj", "down_proj"],
    lora_alpha     = 16,
    lora_dropout   = 0,
    bias           = "none",
    use_gradient_checkpointing = "unsloth",
    random_state   = 42,
)

# ---------------------------------------------------------------------------
# Dataset
# ---------------------------------------------------------------------------

def format_conversation(example):
    """Formatta ogni esempio nel template chat di Mistral."""
    convs = example.get("conversations", [])
    system  = next((c["value"] for c in convs if c["role"] == "system"), "")
    user    = next((c["value"] for c in convs if c["role"] == "user"), "")
    assistant = next((c["value"] for c in convs if c["role"] == "assistant"), "")

    text = tokenizer.apply_chat_template(
        [
            {"role": "system",    "content": system},
            {"role": "user",      "content": user},
            {"role": "assistant", "content": assistant},
        ],
        tokenize=False,
        add_generation_prompt=False,
    )
    return {"text": text}

dataset = load_dataset("json", data_files=DATASET_FILE, split="train")
dataset = dataset.map(format_conversation, remove_columns=dataset.column_names)

print(f"Dataset caricato: {len(dataset)} esempi")
print("Esempio formattato:\n", dataset[0]["text"][:500])

# ---------------------------------------------------------------------------
# Training
# ---------------------------------------------------------------------------

trainer = SFTTrainer(
    model     = model,
    tokenizer = tokenizer,
    train_dataset = dataset,
    dataset_text_field = "text",
    max_seq_length = MAX_SEQ_LENGTH,
    args = TrainingArguments(
        output_dir              = OUTPUT_DIR,
        num_train_epochs        = 3,
        per_device_train_batch_size = 2,
        gradient_accumulation_steps = 4,
        warmup_steps            = 10,
        learning_rate           = 2e-4,
        fp16                    = not torch.cuda.is_bf16_supported(),
        bf16                    = torch.cuda.is_bf16_supported(),
        logging_steps           = 10,
        save_steps              = 100,
        optim                   = "adamw_8bit",
        weight_decay            = 0.01,
        lr_scheduler_type       = "linear",
        seed                    = 42,
        report_to               = "none",
    ),
)

print("\n=== Avvio training QLoRA ===")
trainer.train()
print("=== Training completato ===\n")

# ---------------------------------------------------------------------------
# Salvataggio: modello fuso (merged) in float16 per Ollama
# ---------------------------------------------------------------------------

MERGED_DIR = "./utcbim-merged"
print(f"Salvataggio modello fuso in {MERGED_DIR} ...")

model.save_pretrained_merged(
    MERGED_DIR,
    tokenizer,
    save_method = "merged_16bit",
)

print(f"""
=== Modello pronto ===
Percorso: {os.path.abspath(MERGED_DIR)}

Prossimi passi:
  1. Copia il Modelfile in {MERGED_DIR}/
  2. Esegui: ollama create utcbim-assistant -f Modelfile
  3. Testa: ollama run utcbim-assistant
""")
