"""
Scansione HTTP live dell'applicazione web.
Verifica header di sicurezza, esposizione di file sensibili,
configurazione cookie e information disclosure.
"""

import re
import requests
from urllib.parse import urljoin
from checks.static import Finding

TIMEOUT = 10
HEADERS = {"User-Agent": "UtcBim-SecurityScanner/1.0"}


# ── Header di sicurezza attesi ─────────────────────────────────────────────────

SECURITY_HEADERS = [
    {
        "header": "X-Frame-Options",
        "id": "HDR-001",
        "title": "Header X-Frame-Options mancante",
        "severity": "MEDIUM",
        "category": "A05 - Security Misconfiguration",
        "description": "Senza X-Frame-Options il sito è vulnerabile a attacchi clickjacking.",
        "remediation": "Aggiungere X-Frame-Options: SAMEORIGIN nella configurazione Apache/Nginx.",
    },
    {
        "header": "X-Content-Type-Options",
        "id": "HDR-002",
        "title": "Header X-Content-Type-Options mancante",
        "severity": "LOW",
        "category": "A05 - Security Misconfiguration",
        "description": "Senza nosniff il browser può fare MIME-type sniffing su risorse non fidate.",
        "remediation": "Aggiungere X-Content-Type-Options: nosniff.",
    },
    {
        "header": "X-XSS-Protection",
        "id": "HDR-003",
        "title": "Header X-XSS-Protection mancante",
        "severity": "LOW",
        "category": "A05 - Security Misconfiguration",
        "description": "Assente il filtro XSS del browser (legacy ma ancora utile per browser datati).",
        "remediation": "Aggiungere X-XSS-Protection: 1; mode=block.",
    },
    {
        "header": "Strict-Transport-Security",
        "id": "HDR-004",
        "title": "Header HSTS mancante",
        "severity": "MEDIUM",
        "category": "A02 - Cryptographic Failures",
        "description": "Senza HSTS il browser non forza HTTPS, esponendo a attacchi downgrade.",
        "remediation": "Aggiungere Strict-Transport-Security: max-age=31536000; includeSubDomains.",
    },
    {
        "header": "Content-Security-Policy",
        "id": "HDR-005",
        "title": "Header Content-Security-Policy mancante",
        "severity": "MEDIUM",
        "category": "A03 - Injection / XSS",
        "description": "Senza CSP il browser non ha restrizioni su quali script/risorse può caricare.",
        "remediation": "Definire una Content-Security-Policy restrittiva (almeno default-src 'self').",
    },
    {
        "header": "Referrer-Policy",
        "id": "HDR-006",
        "title": "Header Referrer-Policy mancante",
        "severity": "LOW",
        "category": "A05 - Security Misconfiguration",
        "description": "Senza Referrer-Policy l'URL pieno viene inviato a siti terzi.",
        "remediation": "Aggiungere Referrer-Policy: strict-origin-when-cross-origin.",
    },
    {
        "header": "Permissions-Policy",
        "id": "HDR-007",
        "title": "Header Permissions-Policy mancante",
        "severity": "LOW",
        "category": "A05 - Security Misconfiguration",
        "description": "Senza Permissions-Policy le API del browser (camera, mic, geolocalizzazione) non sono limitate.",
        "remediation": "Aggiungere Permissions-Policy: geolocation=(), microphone=(), camera=().",
    },
]


# ── File sensibili da verificare ───────────────────────────────────────────────

SENSITIVE_PATHS = [
    ("/.git/HEAD",          "EXPO-001", "Repository Git esposto",          "CRITICAL"),
    ("/.git/config",        "EXPO-002", "Configurazione Git esposta",       "CRITICAL"),
    ("/composer.json",      "EXPO-003", "composer.json accessibile",        "MEDIUM"),
    ("/composer.lock",      "EXPO-004", "composer.lock accessibile",        "MEDIUM"),
    ("/.env",               "EXPO-005", "File .env esposto",                "CRITICAL"),
    ("/config/db.php",      "EXPO-006", "File config/db.php accessibile",   "CRITICAL"),
    ("/phpinfo.php",        "EXPO-007", "phpinfo() esposto",                "HIGH"),
    ("/info.php",           "EXPO-008", "phpinfo() esposto (info.php)",     "HIGH"),
    ("/test.php",           "EXPO-009", "File di test esposto",             "MEDIUM"),
    ("/backup/",            "EXPO-010", "Directory backup accessibile",     "HIGH"),
    ("/web/backups/",       "EXPO-011", "Directory web/backups accessibile","HIGH"),
    ("/.htaccess",          "EXPO-012", ".htaccess leggibile",              "LOW"),
    ("/runtime/logs/",      "EXPO-013", "Log di runtime accessibili",       "HIGH"),
    ("/adminer.php",        "EXPO-014", "Adminer database tool esposto",    "CRITICAL"),
    ("/phpmyadmin/",        "EXPO-015", "phpMyAdmin esposto",               "HIGH"),
],


def _make_finding(id, title, severity, category, description, remediation, url="") -> Finding:
    return Finding(
        id=id, title=title, severity=severity,
        category=category, description=description,
        remediation=remediation, file=url, line=0, evidence="",
    )


def check_headers(base_url: str) -> list[Finding]:
    """Verifica la presenza degli header di sicurezza HTTP."""
    findings = []
    try:
        resp = requests.get(base_url, timeout=TIMEOUT, headers=HEADERS, allow_redirects=True, verify=False)
    except requests.RequestException as e:
        findings.append(_make_finding(
            "CONN-001", "Impossibile connettersi all'applicazione", "INFO",
            "A05 - Security Misconfiguration",
            f"Errore di connessione: {e}",
            "Verificare che l'applicazione sia in esecuzione.", base_url,
        ))
        return findings

    resp_headers_lower = {k.lower(): v for k, v in resp.headers.items()}

    for rule in SECURITY_HEADERS:
        if rule["header"].lower() not in resp_headers_lower:
            findings.append(_make_finding(
                rule["id"], rule["title"], rule["severity"],
                rule["category"], rule["description"], rule["remediation"], base_url,
            ))

    # Server version disclosure
    server = resp.headers.get("Server", "")
    if re.search(r"Apache/[\d.]+|nginx/[\d.]+|PHP/[\d.]+", server, re.IGNORECASE):
        findings.append(_make_finding(
            "DISC-001", "Server version disclosure nell'header Server",
            "LOW", "A05 - Security Misconfiguration",
            f"L'header Server rivela la versione: {server}",
            "Nascondere la versione del server (ServerTokens Prod in Apache).",
            base_url,
        ))

    # X-Powered-By
    powered = resp.headers.get("X-Powered-By", "")
    if powered:
        findings.append(_make_finding(
            "DISC-002", "X-Powered-By rivela tecnologia utilizzata",
            "LOW", "A05 - Security Misconfiguration",
            f"X-Powered-By: {powered}",
            "Disabilitare con Header unset X-Powered-By in Apache o expose_php=Off in php.ini.",
            base_url,
        ))

    return findings


def check_cookies(base_url: str) -> list[Finding]:
    """Verifica gli attributi di sicurezza dei cookie."""
    findings = []
    try:
        resp = requests.get(base_url, timeout=TIMEOUT, headers=HEADERS, allow_redirects=True, verify=False)
    except requests.RequestException:
        return findings

    for cookie in resp.cookies:
        if not cookie.has_nonstandard_attr("HttpOnly") and not cookie._rest.get("HttpOnly"):
            findings.append(_make_finding(
                "COOK-001", f"Cookie '{cookie.name}' senza flag HttpOnly",
                "MEDIUM", "A05 - Security Misconfiguration",
                "Un cookie senza HttpOnly è accessibile via JavaScript (rischio XSS).",
                "Impostare session.cookie_httponly = 1 in php.ini e il flag HttpOnly su tutti i cookie.",
                base_url,
            ))
        if not cookie.secure:
            findings.append(_make_finding(
                "COOK-002", f"Cookie '{cookie.name}' senza flag Secure",
                "MEDIUM", "A02 - Cryptographic Failures",
                "Un cookie senza Secure può essere trasmesso su connessioni HTTP non cifrate.",
                "Impostare session.cookie_secure = 1 in php.ini e il flag Secure su tutti i cookie.",
                base_url,
            ))

    return findings


def check_sensitive_paths(base_url: str) -> list[Finding]:
    """Verifica l'accessibilità di file e directory sensibili."""
    findings = []
    paths = SENSITIVE_PATHS[0] if isinstance(SENSITIVE_PATHS[0], list) else SENSITIVE_PATHS

    for item in paths:
        path, fid, title, severity = item
        url = urljoin(base_url.rstrip("/") + "/", path.lstrip("/"))
        try:
            resp = requests.get(url, timeout=TIMEOUT, headers=HEADERS, allow_redirects=False, verify=False)
            if resp.status_code in (200, 403):
                # 403 = esiste ma proibito — ancora un'informazione
                evidence = f"HTTP {resp.status_code} — {url}"
                category = "A01 - Broken Access Control" if severity in ("CRITICAL", "HIGH") else "A05 - Security Misconfiguration"
                description = f"Il percorso '{path}' è raggiungibile (HTTP {resp.status_code})."
                remediation = "Bloccare l'accesso tramite regole .htaccess o configurazione del web server."
                findings.append(Finding(
                    id=fid, title=title, severity=severity,
                    category=category, description=description,
                    remediation=remediation, file=url, line=0, evidence=evidence,
                ))
        except requests.RequestException:
            pass

    return findings


def check_tls(base_url: str) -> list[Finding]:
    """Verifica la presenza di HTTPS."""
    findings = []
    if base_url.startswith("http://"):
        findings.append(_make_finding(
            "TLS-001", "Applicazione servita su HTTP (non HTTPS)",
            "HIGH", "A02 - Cryptographic Failures",
            "Il traffico non è cifrato — credenziali e dati sensibili trasmessi in chiaro.",
            "Abilitare HTTPS con un certificato valido e redirigere tutto il traffico HTTP → HTTPS.",
            base_url,
        ))
        # Prova se esiste anche una versione HTTPS
        https_url = base_url.replace("http://", "https://", 1)
        try:
            resp = requests.get(https_url, timeout=TIMEOUT, headers=HEADERS, verify=False)
            if resp.status_code < 400:
                findings.append(_make_finding(
                    "TLS-002", "HTTPS disponibile ma HTTP non viene rediretto",
                    "MEDIUM", "A02 - Cryptographic Failures",
                    "La versione HTTPS esiste ma HTTP non viene automaticamente reindirizzato.",
                    "Aggiungere un redirect 301 da HTTP a HTTPS nel virtual host Apache.",
                    base_url,
                ))
        except requests.RequestException:
            pass
    return findings


def run_all(base_url: str) -> list[Finding]:
    """Esegue tutti i controlli HTTP e restituisce i finding."""
    import urllib3
    urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

    findings = []
    findings += check_tls(base_url)
    findings += check_headers(base_url)
    findings += check_cookies(base_url)
    findings += check_sensitive_paths(base_url)
    return findings
