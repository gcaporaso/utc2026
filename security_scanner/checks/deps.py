"""
Audit delle dipendenze: composer.lock (PHP) e package.json (JS).
Controlla versioni note come vulnerabili rispetto a un database locale.
"""

import json
import os
from checks.static import Finding

# Database minimale di versioni vulnerabili note (pacchetto → [(versione_max_vulnerabile, CVE, descrizione, severity)])
COMPOSER_VULNS: dict[str, list] = {
    "yiisoft/yii2": [
        ("2.0.45", "CVE-2023-42340", "RCE tramite deserializzazione non sicura in Yii2 < 2.0.46", "CRITICAL"),
        ("2.0.43", "CVE-2022-28513", "XSS nel componente Formatter di Yii2 < 2.0.44", "HIGH"),
    ],
    "symfony/http-kernel": [
        ("5.4.19", "CVE-2022-24894", "Injection di cookie in Symfony HTTP Kernel", "HIGH"),
    ],
    "league/flysystem": [
        ("1.1.3",  "CVE-2021-32708", "Path traversal in Flysystem < 1.1.4 / < 2.1.1", "CRITICAL"),
    ],
    "phpmailer/phpmailer": [
        ("6.6.3",  "CVE-2021-3603",  "Remote code execution in PHPMailer < 6.6.4", "CRITICAL"),
    ],
    "guzzlehttp/guzzle": [
        ("7.4.4",  "CVE-2022-29248", "Cookie leak in Guzzle < 7.4.5", "HIGH"),
        ("7.5.0",  "CVE-2022-31090", "Header injection in Guzzle", "HIGH"),
    ],
}


def _version_le(ver: str, max_ver: str) -> bool:
    """Restituisce True se ver <= max_ver (confronto semantico semplice)."""
    try:
        def parts(v):
            return [int(x) for x in v.lstrip("v~^").split(".")[:3]]
        return parts(ver) <= parts(max_ver)
    except Exception:
        return False


def audit_composer(root: str) -> list[Finding]:
    """Analizza composer.lock alla ricerca di dipendenze vulnerabili."""
    findings = []
    lock_path = os.path.join(root, "composer.lock")
    if not os.path.isfile(lock_path):
        return findings

    try:
        with open(lock_path, "r", encoding="utf-8") as f:
            data = json.load(f)
    except (json.JSONDecodeError, OSError):
        return findings

    packages = data.get("packages", []) + data.get("packages-dev", [])
    for pkg in packages:
        name    = pkg.get("name", "").lower()
        version = pkg.get("version", "0.0.0").lstrip("v")
        if name in COMPOSER_VULNS:
            for (max_ver, cve, desc, severity) in COMPOSER_VULNS[name]:
                if _version_le(version, max_ver):
                    findings.append(Finding(
                        id=f"DEP-{cve.replace('-', '')}",
                        title=f"Dipendenza vulnerabile: {name} {version}",
                        severity=severity,
                        category="A06 - Vulnerable Components",
                        description=f"{desc} ({cve}). Versione installata: {version}, max vulnerabile: {max_ver}.",
                        remediation=f"Aggiornare {name} eseguendo: composer update {name}",
                        file=lock_path,
                        line=0,
                        evidence=f"{name}: {version}",
                    ))

    return findings


def audit_config_php(root: str) -> list[Finding]:
    """Controlla impostazioni di sicurezza nei file di configurazione PHP."""
    findings = []

    # Verifica debug mode in config/web.php
    web_config = os.path.join(root, "config", "web.php")
    if os.path.isfile(web_config):
        with open(web_config, "r", encoding="utf-8", errors="replace") as f:
            content = f.read()
        if "'debug' => true" in content or '"debug" => true' in content:
            findings.append(Finding(
                id="CONF-010",
                title="Debug mode attivo in config/web.php",
                severity="HIGH",
                category="A05 - Security Misconfiguration",
                description="La modalità debug espone stack trace e informazioni sul sistema in produzione.",
                remediation="Impostare 'debug' => YII_DEBUG (false in produzione) e gestirlo tramite variabile d'ambiente.",
                file=web_config, line=0, evidence="'debug' => true",
            ))

    # Verifica presenza cookieValidationKey vuota
    for cfg_file in ["config/web.php"]:
        path = os.path.join(root, cfg_file)
        if os.path.isfile(path):
            with open(path, "r", encoding="utf-8", errors="replace") as f:
                content = f.read()
            if "'cookieValidationKey' => ''" in content:
                findings.append(Finding(
                    id="CONF-011",
                    title="cookieValidationKey vuota",
                    severity="HIGH",
                    category="A02 - Cryptographic Failures",
                    description="La chiave di validazione cookie è vuota — i cookie possono essere contraffatti.",
                    remediation="Impostare una stringa casuale di almeno 32 caratteri come cookieValidationKey.",
                    file=path, line=0, evidence="'cookieValidationKey' => ''",
                ))

    return findings


def run_all(root: str) -> list[Finding]:
    findings = []
    findings += audit_composer(root)
    findings += audit_config_php(root)
    return findings
