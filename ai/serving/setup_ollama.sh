#!/bin/bash
# =============================================================================
# setup_ollama.sh — Installa Ollama e crea il modello UTC-BIM
#
# Eseguire DOPO aver completato il fine-tuning (train_unsloth.py)
# oppure per usare il modello base Mistral senza fine-tuning.
#
# Uso:
#   chmod +x setup_ollama.sh
#   ./setup_ollama.sh [--base]       # --base = usa Mistral senza fine-tuning
# =============================================================================

set -e

USE_BASE=false
[[ "$1" == "--base" ]] && USE_BASE=true

# ---------------------------------------------------------------------------
# 1. Installa Ollama (se non già installato)
# ---------------------------------------------------------------------------
if ! command -v ollama &> /dev/null; then
    echo "[1/4] Installazione Ollama..."
    curl -fsSL https://ollama.com/install.sh | sh
    echo "      OK"
else
    echo "[1/4] Ollama già installato: $(ollama --version)"
fi

# ---------------------------------------------------------------------------
# 2. Avvia il server Ollama in background (se non attivo)
# ---------------------------------------------------------------------------
echo "[2/4] Avvio server Ollama..."
if ! pgrep -x "ollama" > /dev/null; then
    ollama serve &
    sleep 3
    echo "      OK (avviato)"
else
    echo "      OK (già in esecuzione)"
fi

# ---------------------------------------------------------------------------
# 3. Crea il modello UTC-BIM
# ---------------------------------------------------------------------------
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ "$USE_BASE" = true ]; then
    echo "[3/4] Creazione modello UTC-BIM (base Mistral, senza fine-tuning)..."
    # Modelfile temporaneo che usa il modello scaricato da Ollama registry
    TMP_MF=$(mktemp)
    cat > "$TMP_MF" << 'MODELFILE_EOF'
FROM mistral:7b-instruct
PARAMETER temperature 0.1
PARAMETER top_p 0.9
PARAMETER num_predict 512
SYSTEM """Sei l'assistente AI per il sistema UTC-BIM del Comune di Campoli del Monte Taburno.
Interpreta le domande degli utenti e rispondi SOLO con JSON valido.
FORMATO: SQL→{"type":"sql","query":"SELECT...LIMIT 100"} | Mappa→{"type":"map","action":"...","params":{}} | Non chiaro→{"type":"clarify","message":"..."}
REGOLE: Solo SELECT, LIMIT 100 default, LIKE '%...%' per testi."""
MODELFILE_EOF
    ollama create utcbim-assistant -f "$TMP_MF"
    rm -f "$TMP_MF"
else
    echo "[3/4] Creazione modello UTC-BIM (modello fine-tuned)..."
    MERGED_DIR="$(dirname "$SCRIPT_DIR")/finetune/utcbim-merged"
    if [ ! -d "$MERGED_DIR" ]; then
        echo "ERRORE: Modello fine-tuned non trovato in $MERGED_DIR"
        echo "Esegui prima: cd ai/finetune && python train_unsloth.py"
        echo "Oppure usa --base per il modello base Mistral."
        exit 1
    fi
    cp "$SCRIPT_DIR/Modelfile" "$MERGED_DIR/Modelfile"
    cd "$MERGED_DIR"
    ollama create utcbim-assistant -f Modelfile
fi

echo "      OK"

# ---------------------------------------------------------------------------
# 4. Test rapido
# ---------------------------------------------------------------------------
echo "[4/4] Test del modello..."
RESULT=$(ollama run utcbim-assistant "Quante pratiche edilizie ci sono?" 2>&1 || true)
echo "      Risposta test: $RESULT"

# ---------------------------------------------------------------------------
echo ""
echo "========================================================"
echo "  SETUP COMPLETATO"
echo "  Modello: utcbim-assistant"
echo "  Endpoint: http://localhost:11434/v1/chat/completions"
echo "========================================================"
echo ""
echo "Per avviare Ollama come servizio systemd:"
echo "  sudo systemctl enable ollama"
echo "  sudo systemctl start ollama"
