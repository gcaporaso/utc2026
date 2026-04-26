"""
Analisi statica del codice sorgente PHP/JS.
Cerca pattern associati alle vulnerabilità OWASP Top 10.
"""

import os
import re
from dataclasses import dataclass, field
from typing import Optional

# ── Struttura di un finding ────────────────────────────────────────────────────

@dataclass
class Finding:
    id:          str
    title:       str
    severity:    str          # CRITICAL / HIGH / MEDIUM / LOW / INFO
    category:    str          # OWASP category
    description: str
    remediation: str
    file:        str = ""
    line:        int = 0
    evidence:    str = ""


# ── Regole statiche ────────────────────────────────────────────────────────────

RULES = [
    # --- A03 Injection: SQL ---
    {
        "id": "SQLI-001",
        "title": "Possibile SQL Injection — input utente in query",
        "severity": "CRITICAL",
        "category": "A03 - Injection",
        "pattern": re.compile(
            r'(\$_(?:GET|POST|REQUEST|COOKIE)\s*\[[^\]]+\])'
            r'.*?'
            r'(SELECT|INSERT|UPDATE|DELETE|WHERE|AND\s|OR\s)',
            re.IGNORECASE | re.DOTALL,
        ),
        "description": "Un valore proveniente dalla richiesta HTTP viene usato direttamente in una query SQL senza parametrizzazione.",
        "remediation": "Usare query parametrizzate (PDO/prepared statements o l'ORM di Yii2).",
        "extensions": [".php"],
    },
    {
        "id": "SQLI-002",
        "title": "Concatenazione diretta di variabili in query SQL",
        "severity": "HIGH",
        "category": "A03 - Injection",
        "pattern": re.compile(
            r'(?:SELECT|INSERT|UPDATE|DELETE|WHERE)[^;\'\"]*\.\s*\$(?!this)[a-zA-Z_]\w*',
            re.IGNORECASE,
        ),
        "description": "Una variabile PHP viene concatenata direttamente in una stringa SQL.",
        "remediation": "Usare bindParam/bindValue o il query builder di Yii2.",
        "extensions": [".php"],
    },

    # --- A03 Injection: Command ---
    {
        "id": "CMDI-001",
        "title": "Command Injection — exec/system con input utente",
        "severity": "CRITICAL",
        "category": "A03 - Injection",
        "pattern": re.compile(
            r'(?:exec|system|shell_exec|passthru|popen|proc_open)\s*\('
            r'[^)]*\$_(?:GET|POST|REQUEST|COOKIE)',
            re.IGNORECASE,
        ),
        "description": "Un comando di shell viene costruito con input controllato dall'utente.",
        "remediation": "Usare escapeshellarg() / escapeshellcmd() e validare l'input con whitelist.",
        "extensions": [".php"],
    },
    {
        "id": "CMDI-002",
        "title": "Uso di eval() con variabile",
        "severity": "CRITICAL",
        "category": "A03 - Injection",
        "pattern": re.compile(r'\beval\s*\(\s*\$', re.IGNORECASE),
        "description": "eval() esegue codice PHP arbitrario. Se la variabile include input utente è critico.",
        "remediation": "Eliminare eval(). Riscrivere la logica senza esecuzione dinamica di codice.",
        "extensions": [".php"],
    },

    # --- A03 Injection: XSS ---
    {
        "id": "XSS-001",
        "title": "XSS Reflected — echo di input utente senza encoding",
        "severity": "HIGH",
        "category": "A03 - Injection / A07 - XSS",
        "pattern": re.compile(
            r'echo\s+\$_(?:GET|POST|REQUEST|COOKIE)\s*\[',
            re.IGNORECASE,
        ),
        "description": "Un valore dalla richiesta HTTP viene stampato direttamente senza htmlspecialchars().",
        "remediation": "Usare Html::encode() di Yii2 o htmlspecialchars($val, ENT_QUOTES, 'UTF-8').",
        "extensions": [".php"],
    },
    {
        "id": "XSS-002",
        "title": "Output non sicuro con print_r/var_dump in produzione",
        "severity": "MEDIUM",
        "category": "A05 - Misconfiguration",
        "pattern": re.compile(r'\b(?:print_r|var_dump|var_export)\s*\(', re.IGNORECASE),
        "description": "Funzioni di debug lasciate nel codice di produzione possono esporre strutture interne.",
        "remediation": "Rimuovere o proteggere con un flag di debug.",
        "extensions": [".php"],
    },

    # --- A02 Cryptographic Failures ---
    {
        "id": "CRYPT-001",
        "title": "Uso di MD5 per hash (algoritmo insicuro)",
        "severity": "HIGH",
        "category": "A02 - Cryptographic Failures",
        "pattern": re.compile(r'\bmd5\s*\(', re.IGNORECASE),
        "description": "MD5 è crittograficamente rotto e non deve essere usato per hash di password o integrità.",
        "remediation": "Usare password_hash() con PASSWORD_BCRYPT o PASSWORD_ARGON2ID.",
        "extensions": [".php"],
    },
    {
        "id": "CRYPT-002",
        "title": "Uso di SHA1 per hash (algoritmo debole)",
        "severity": "MEDIUM",
        "category": "A02 - Cryptographic Failures",
        "pattern": re.compile(r'\bsha1\s*\(', re.IGNORECASE),
        "description": "SHA1 è considerato debole per applicazioni crittografiche.",
        "remediation": "Usare SHA-256 o superiore (hash('sha256', ...)) per integrità; password_hash() per password.",
        "extensions": [".php"],
    },

    # --- A02 Hardcoded Credentials ---
    {
        "id": "CRED-001",
        "title": "Credenziali hardcoded nel codice",
        "severity": "HIGH",
        "category": "A02 - Cryptographic Failures",
        "pattern": re.compile(
            r'''(?:password|passwd|secret|api_key|apikey|token)\s*[=:]\s*['""][^'""\s]{6,}['""]]''',
            re.IGNORECASE,
        ),
        "description": "Credenziali o chiavi segrete sono hardcoded nel codice sorgente.",
        "remediation": "Spostare le credenziali in variabili d'ambiente o file di configurazione esclusi dal repository.",
        "extensions": [".php", ".py", ".js", ".env"],
    },

    # --- A01 Broken Access Control ---
    {
        "id": "AUTHZ-001",
        "title": "Accesso diretto a file tramite path controllato dall'utente",
        "severity": "HIGH",
        "category": "A01 - Broken Access Control",
        "pattern": re.compile(
            r'(?:include|require|include_once|require_once|fopen|file_get_contents|readfile)\s*\('
            r'[^)]*\$_(?:GET|POST|REQUEST)',
            re.IGNORECASE,
        ),
        "description": "Un path di file viene costruito con input utente — rischio path traversal / LFI.",
        "remediation": "Validare e normalizzare il path con realpath(). Usare una whitelist di file consentiti.",
        "extensions": [".php"],
    },
    {
        "id": "AUTHZ-002",
        "title": "Redirect non validato (Open Redirect)",
        "severity": "MEDIUM",
        "category": "A01 - Broken Access Control",
        "pattern": re.compile(
            r'(?:header\s*\(\s*[\'"]Location:|->redirect\s*\()'
            r'[^)]*\$_(?:GET|POST|REQUEST)',
            re.IGNORECASE,
        ),
        "description": "L'URL di redirect è controllato dall'utente — possibile open redirect usabile per phishing.",
        "remediation": "Validare l'URL contro una whitelist di domini interni prima del redirect.",
        "extensions": [".php"],
    },

    # --- A05 Security Misconfiguration ---
    {
        "id": "CONF-001",
        "title": "Modalità debug abilitata",
        "severity": "MEDIUM",
        "category": "A05 - Security Misconfiguration",
        "pattern": re.compile(r"['\"]debug['\"]\s*=>\s*true", re.IGNORECASE),
        "description": "La modalità debug espone stack trace e informazioni di sistema.",
        "remediation": "Impostare 'debug' => false e 'env' => 'prod' in configurazione produzione.",
        "extensions": [".php"],
    },
    {
        "id": "CONF-002",
        "title": "Soppressione di errori con @",
        "severity": "LOW",
        "category": "A05 - Security Misconfiguration",
        "pattern": re.compile(r'@\s*(?:unlink|file_get_contents|include|fopen|mkdir)', re.IGNORECASE),
        "description": "L'operatore @ nasconde errori che possono mascherare condizioni di sicurezza.",
        "remediation": "Gestire gli errori esplicitamente con try/catch o controllo del valore di ritorno.",
        "extensions": [".php"],
    },

    # --- A09 Logging Failures ---
    {
        "id": "LOG-001",
        "title": "Operazione sensibile senza logging",
        "severity": "LOW",
        "category": "A09 - Logging Failures",
        "pattern": re.compile(
            r'(?:->delete\(\)|->deleteAll\(|DROP\s+TABLE|TRUNCATE)',
            re.IGNORECASE,
        ),
        "description": "Operazioni distruttive sul database senza evidenza di logging dell'evento.",
        "remediation": "Aggiungere un audit log per tutte le operazioni di cancellazione dati.",
        "extensions": [".php"],
    },

    # --- A08 Integrity: script esterni senza SRI ---
    {
        "id": "SRI-001",
        "title": "Script/CSS esterno senza Subresource Integrity",
        "severity": "LOW",
        "category": "A08 - Software Integrity Failures",
        "pattern": re.compile(
            r'<(?:script|link)[^>]+(?:src|href)\s*=\s*[\'"]https?://(?!(?:192\.168|100\.|localhost))[^\'"]+[\'"][^>]*>(?!.*integrity)',
            re.IGNORECASE,
        ),
        "description": "Una risorsa esterna viene caricata senza attributo integrity (SRI).",
        "remediation": "Aggiungere integrity='sha384-...' crossorigin='anonymous' alle risorse CDN.",
        "extensions": [".php", ".html"],
    },
]


# ── Scanner ────────────────────────────────────────────────────────────────────

def _strip_comments(lines: list[str], ext: str) -> list[tuple[int, str]]:
    """
    Restituisce solo le righe di codice attivo (numero 1-based, testo).
    Gestisce commenti // # (riga) e blocchi /* */ per PHP/JS.
    """
    result = []
    in_block = False
    for i, raw in enumerate(lines, start=1):
        line = raw

        if ext in (".php", ".js"):
            if in_block:
                end = line.find("*/")
                if end == -1:
                    continue          # riga interamente dentro un blocco commento
                line = line[end + 2:]
                in_block = False

            # rimuovi eventuale blocco /* ... */ tutto sulla stessa riga
            while True:
                start = line.find("/*")
                if start == -1:
                    break
                end = line.find("*/", start + 2)
                if end == -1:
                    line = line[:start]
                    in_block = True
                    break
                line = line[:start] + line[end + 2:]

            # rimuovi commento di riga // o #
            stripped = line.lstrip()
            if stripped.startswith("//") or stripped.startswith("#"):
                continue

            # tronca la parte dopo // o # inline (fuori da stringhe — approssimazione)
            for marker in ("//"," #"):
                pos = line.find(marker)
                if pos != -1:
                    line = line[:pos]

        if line.strip():
            result.append((i, line))

    return result


def scan_file(filepath: str) -> list[Finding]:
    """Applica tutte le regole statiche a un singolo file."""
    ext = os.path.splitext(filepath)[1].lower()
    findings = []

    try:
        with open(filepath, "r", encoding="utf-8", errors="replace") as f:
            lines = f.readlines()
    except OSError:
        return findings

    active_lines = _strip_comments(lines, ext)

    for rule in RULES:
        if ext not in rule["extensions"]:
            continue
        for i, line in active_lines:
            if rule["pattern"].search(line):
                evidence = line.strip()[:120]
                findings.append(Finding(
                    id=rule["id"],
                    title=rule["title"],
                    severity=rule["severity"],
                    category=rule["category"],
                    description=rule["description"],
                    remediation=rule["remediation"],
                    file=filepath,
                    line=i,
                    evidence=evidence,
                ))
    return findings


def scan_directory(root: str, exclude_dirs: Optional[list] = None) -> list[Finding]:
    """Scansiona ricorsivamente una directory."""
    if exclude_dirs is None:
        exclude_dirs = ["vendor", "runtime", "node_modules", ".git", "tests"]

    all_findings: list[Finding] = []
    extensions = {ext for rule in RULES for ext in rule["extensions"]}

    for dirpath, dirnames, filenames in os.walk(root):
        dirnames[:] = [d for d in dirnames if d not in exclude_dirs]
        for fname in filenames:
            if os.path.splitext(fname)[1].lower() in extensions:
                fpath = os.path.join(dirpath, fname)
                all_findings.extend(scan_file(fpath))

    return all_findings
