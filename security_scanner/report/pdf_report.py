"""
Generazione del report PDF con fpdf2.
"""

from datetime import datetime
from collections import Counter
from fpdf import FPDF, XPos, YPos
from checks.static import Finding

SEVERITY_ORDER  = ["CRITICAL", "HIGH", "MEDIUM", "LOW", "INFO"]
SEVERITY_COLORS = {
    "CRITICAL": (220, 50,  50),
    "HIGH":     (230, 120, 40),
    "MEDIUM":   (230, 190, 50),
    "LOW":      (80,  160, 80),
    "INFO":     (100, 140, 200),
}
SEVERITY_SCORES = {"CRITICAL": 4, "HIGH": 3, "MEDIUM": 2, "LOW": 1, "INFO": 0}

_UNICODE_MAP = str.maketrans({
    "—": "--", "–": "-", "‘": "'", "’": "'",
    "“": '"',  "”": '"', "•": "*", "…": "...",
    "à": "a",  "è": "e", "é": "e", "ì": "i",
    "ò": "o",  "ù": "u", "â": "a", "ê": "e",
    "î": "i",  "ô": "o", "û": "u", "ä": "a",
    "ë": "e",  "ï": "i", "ö": "o", "ü": "u",
    "ã": "a",  "õ": "o", "ç": "c", "ñ": "n",
    "→": "->", "▶": ">", "✓": "OK","✗": "X",
    "«": "<<", "»": ">>",
})

def _s(text) -> str:
    """Sanitizza il testo per il font latin-1 di fpdf2."""
    if not text:
        return ""
    return str(text).translate(_UNICODE_MAP).encode("latin-1", errors="replace").decode("latin-1")


class SecurityReport(FPDF):
    def __init__(self, target: str, source: str):
        super().__init__()
        self.target = target
        self.source = source
        self.set_auto_page_break(auto=True, margin=20)
        self.set_margins(20, 20, 20)

    # ── Header / Footer ────────────────────────────────────────────────────────

    def header(self):
        if self.page_no() == 1:
            return
        self.set_font("Helvetica", "B", 8)
        self.set_text_color(120, 120, 120)
        self.cell(0, 8, "UTC BIM -- Security Vulnerability Report", align="L")
        self.set_font("Helvetica", "", 8)
        self.cell(0, 8, f"Pagina {self.page_no()}", align="R", new_x=XPos.LMARGIN, new_y=YPos.NEXT)
        self.set_draw_color(200, 200, 200)
        self.line(20, self.get_y(), 190, self.get_y())
        self.ln(3)
        self.set_text_color(0, 0, 0)

    def footer(self):
        self.set_y(-15)
        self.set_font("Helvetica", "I", 7)
        self.set_text_color(150, 150, 150)
        self.cell(0, 5, _s(f"Generato il {datetime.now().strftime('%d/%m/%Y %H:%M')} -- Uso interno riservato"), align="C")

    # ── Helpers ────────────────────────────────────────────────────────────────

    def _h1(self, text: str):
        self.set_font("Helvetica", "B", 16)
        self.set_text_color(30, 70, 140)
        self.cell(0, 10, _s(text), new_x=XPos.LMARGIN, new_y=YPos.NEXT)
        self.set_draw_color(30, 70, 140)
        self.line(20, self.get_y(), 190, self.get_y())
        self.ln(4)
        self.set_text_color(0, 0, 0)

    def _h2(self, text: str):
        self.set_font("Helvetica", "B", 12)
        self.set_text_color(50, 50, 50)
        self.cell(0, 8, _s(text), new_x=XPos.LMARGIN, new_y=YPos.NEXT)
        self.ln(2)
        self.set_text_color(0, 0, 0)

    def _body(self, text: str, size: int = 10):
        self.set_font("Helvetica", "", size)
        self.multi_cell(0, 5.5, _s(text))
        self.ln(1)

    def _badge(self, severity: str):
        r, g, b = SEVERITY_COLORS.get(severity, (150, 150, 150))
        self.set_fill_color(r, g, b)
        self.set_text_color(255, 255, 255)
        self.set_font("Helvetica", "B", 8)
        self.cell(22, 6, severity, fill=True, align="C")
        self.set_text_color(0, 0, 0)

    def _kv(self, key: str, value: str, indent: int = 0):
        self.set_x(20 + indent)
        self.set_font("Helvetica", "B", 9)
        self.cell(32, 5.5, _s(key + ":"), new_x=XPos.RIGHT, new_y=YPos.TOP)
        self.set_font("Helvetica", "", 9)
        self.multi_cell(0, 5.5, _s(value or "-"))

    # ── Cover page ─────────────────────────────────────────────────────────────

    def cover(self, findings: list[Finding]):
        self.add_page()
        self.set_fill_color(25, 60, 130)
        self.rect(0, 0, 210, 70, "F")

        self.set_y(18)
        self.set_font("Helvetica", "B", 26)
        self.set_text_color(255, 255, 255)
        self.cell(0, 12, "Security Vulnerability Report", align="C", new_x=XPos.LMARGIN, new_y=YPos.NEXT)
        self.set_font("Helvetica", "", 14)
        self.cell(0, 8, "UTC BIM -- Analisi di sicurezza applicazione web", align="C", new_x=XPos.LMARGIN, new_y=YPos.NEXT)

        self.set_y(80)
        self.set_text_color(0, 0, 0)
        self.set_font("Helvetica", "", 11)

        info = [
            ("Target",          self.target),
            ("Sorgente",        self.source),
            ("Data scansione",  datetime.now().strftime("%d/%m/%Y %H:%M")),
            ("Tool",            "UTC BIM Security Scanner v1.0"),
        ]
        for k, v in info:
            self._kv(k, v)
            self.ln(1)

        self.ln(8)
        counts = Counter(f.severity for f in findings)
        total  = len(findings)

        self.set_font("Helvetica", "B", 12)
        self.cell(0, 8, f"Totale finding: {total}", new_x=XPos.LMARGIN, new_y=YPos.NEXT)
        self.ln(3)

        col_w = 34
        for sev in SEVERITY_ORDER:
            r, g, b = SEVERITY_COLORS[sev]
            self.set_fill_color(r, g, b)
            self.set_text_color(255, 255, 255)
            self.set_font("Helvetica", "B", 10)
            self.cell(col_w, 10, sev, fill=True, align="C")
        self.ln()
        self.set_text_color(0, 0, 0)
        for sev in SEVERITY_ORDER:
            r, g, b = SEVERITY_COLORS[sev]
            n = counts.get(sev, 0)
            self.set_text_color(r, g, b) if n > 0 else self.set_text_color(180, 180, 180)
            self.set_font("Helvetica", "B", 13)
            self.cell(col_w, 10, str(n), align="C")
        self.ln()
        self.set_text_color(0, 0, 0)

    # ── Executive summary ──────────────────────────────────────────────────────

    def executive_summary(self, findings: list[Finding]):
        self.add_page()
        self._h1("Executive Summary")

        counts   = Counter(f.severity for f in findings)
        critical = counts.get("CRITICAL", 0)
        high     = counts.get("HIGH", 0)
        total    = len(findings)

        risk = "CRITICO" if critical > 0 else ("ALTO" if high > 2 else ("MEDIO" if high > 0 else "BASSO"))
        self._body(
            f"La scansione ha rilevato {total} potenziali vulnerabilita' nell'applicazione UTC BIM. "
            f"Il livello di rischio complessivo e' valutato: {risk}.\n\n"
            f"Sono presenti {critical} finding CRITICAL e {high} finding HIGH che richiedono "
            f"intervento immediato prima di qualsiasi rilascio in produzione."
        )

        self.ln(3)
        self._h2("Distribuzione per categoria OWASP")
        categories = Counter(f.category for f in findings)
        for cat, n in sorted(categories.items(), key=lambda x: -x[1]):
            self.set_font("Helvetica", "", 10)
            self.cell(130, 6, _s(cat))
            self.set_font("Helvetica", "B", 10)
            self.cell(20, 6, str(n), align="R", new_x=XPos.LMARGIN, new_y=YPos.NEXT)

        self.ln(5)
        self._h2("Priorita' di intervento")
        priorities = [
            ("Immediato (0-7 giorni)", "CRITICAL", "Vulnerabilita' che permettono accesso non autorizzato o esecuzione di codice."),
            ("Breve termine (1 mese)", "HIGH",     "Vulnerabilita' significative che aumentano la superficie di attacco."),
            ("Medio termine (3 mesi)", "MEDIUM",   "Problemi di configurazione e best practice di sicurezza."),
            ("Lungo termine",          "LOW/INFO",  "Miglioramenti e ottimizzazioni della postura di sicurezza."),
        ]
        for timeframe, level, desc in priorities:
            self.set_font("Helvetica", "B", 10)
            self.cell(60, 6, _s(timeframe))
            self.set_font("Helvetica", "", 10)
            self.cell(0, 6, _s(f"[{level}] {desc}"), new_x=XPos.LMARGIN, new_y=YPos.NEXT)

    # ── Findings detail ────────────────────────────────────────────────────────

    def findings_section(self, findings: list[Finding]):
        sorted_findings = sorted(
            findings,
            key=lambda f: (-SEVERITY_SCORES.get(f.severity, 0), f.category, f.file),
        )

        for sev in SEVERITY_ORDER:
            group = [f for f in sorted_findings if f.severity == sev]
            if not group:
                continue

            self.add_page()
            self._h1(f"Finding {sev} ({len(group)})")

            for f in group:
                if self.get_y() > 240:
                    self.add_page()

                self.set_fill_color(245, 245, 245)
                self.set_font("Helvetica", "B", 10)
                self.cell(0, 7, _s(f"[{f.id}] {f.title}"[:90]), fill=True, new_x=XPos.LMARGIN, new_y=YPos.NEXT)

                self.set_x(20)
                self._badge(f.severity)
                self.set_x(46)
                self.set_font("Helvetica", "", 9)
                self.set_text_color(80, 80, 80)
                self.cell(0, 6, _s(f.category), new_x=XPos.LMARGIN, new_y=YPos.NEXT)
                self.set_text_color(0, 0, 0)

                self.ln(1)
                if f.file:
                    rel = f.file.replace(self.source, "").lstrip("/")
                    loc = _s((rel + (f":{f.line}" if f.line else ""))[:90])
                    self._kv("Posizione", loc, indent=2)
                self._kv("Descrizione", f.description, indent=2)
                if f.evidence:
                    self._kv("Evidenza", f.evidence[:100], indent=2)
                self._kv("Rimedio", f.remediation, indent=2)

                self.ln(4)
                self.set_draw_color(220, 220, 220)
                self.line(20, self.get_y(), 190, self.get_y())
                self.ln(4)

    # ── Remediation checklist ──────────────────────────────────────────────────

    def remediation_checklist(self, findings: list[Finding]):
        self.add_page()
        self._h1("Checklist Remediation")

        seen, items = set(), []
        for f in sorted(findings, key=lambda x: -SEVERITY_SCORES.get(x.severity, 0)):
            if f.id not in seen:
                seen.add(f.id)
                items.append(f)

        for f in items:
            if self.get_y() > 265:
                self.add_page()
            self.set_x(20)
            r, g, b = SEVERITY_COLORS.get(f.severity, (150, 150, 150))
            self.set_fill_color(r, g, b)
            self.set_text_color(255, 255, 255)
            self.set_font("Helvetica", "B", 7)
            self.cell(18, 5, f.severity, fill=True, align="C")
            self.set_text_color(0, 0, 0)
            self.set_x(40)
            self.set_font("Helvetica", "B", 9)
            self.cell(0, 5, _s(f"[{f.id}] {f.title[:70]}"), new_x=XPos.LMARGIN, new_y=YPos.NEXT)
            self.set_x(40)
            self.set_font("Helvetica", "", 8)
            self.set_text_color(60, 60, 60)
            self.multi_cell(150, 4.5, _s(f.remediation))
            self.set_text_color(0, 0, 0)
            self.ln(2)


# ── Entry point ────────────────────────────────────────────────────────────────

def generate(findings: list[Finding], output_path: str, target: str, source: str) -> str:
    pdf = SecurityReport(target=target, source=source)
    pdf.cover(findings)
    pdf.executive_summary(findings)
    pdf.findings_section(findings)
    pdf.remediation_checklist(findings)
    pdf.output(output_path)
    return output_path
