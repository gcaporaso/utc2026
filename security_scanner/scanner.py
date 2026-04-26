#!/usr/bin/env python3
"""
UTC BIM Security Scanner
Analisi statica del codice + probe HTTP live + audit dipendenze.
Genera un report PDF dettagliato.

Uso:
  python scanner.py [--target URL] [--source DIR] [--output FILE]

Esempi:
  python scanner.py
  python scanner.py --target http://192.168.1.225 --source /var/www/utcbim
  python scanner.py --output /tmp/report.pdf
"""

import argparse
import sys
import os
from datetime import datetime

sys.path.insert(0, os.path.dirname(__file__))

from checks import static, http_scan, deps
from report import pdf_report


DEFAULT_TARGET = "http://192.168.1.225"
DEFAULT_SOURCE = "/var/www/utcbim"
DEFAULT_OUTPUT = os.path.join(
    os.path.dirname(__file__),
    f"security_report_{datetime.now().strftime('%Y%m%d_%H%M%S')}.pdf",
)

SEVERITY_SCORES = {"CRITICAL": 4, "HIGH": 3, "MEDIUM": 2, "LOW": 1, "INFO": 0}
SEVERITY_ICONS  = {"CRITICAL": "🔴", "HIGH": "🟠", "MEDIUM": "🟡", "LOW": "🟢", "INFO": "🔵"}


def _banner():
    print("\n" + "=" * 60)
    print("  UTC BIM Security Scanner v1.0")
    print("=" * 60)


def _progress(msg: str):
    print(f"  ▶  {msg}")


def _summary(findings):
    from collections import Counter
    counts = Counter(f.severity for f in findings)
    print("\n" + "-" * 60)
    print(f"  Totale finding: {len(findings)}")
    for sev in ["CRITICAL", "HIGH", "MEDIUM", "LOW", "INFO"]:
        n = counts.get(sev, 0)
        icon = SEVERITY_ICONS.get(sev, "")
        print(f"  {icon}  {sev:<10} {n}")
    print("-" * 60)


def main():
    parser = argparse.ArgumentParser(description="UTC BIM Security Scanner")
    parser.add_argument("--target", default=DEFAULT_TARGET,
                        help=f"URL dell'applicazione (default: {DEFAULT_TARGET})")
    parser.add_argument("--source", default=DEFAULT_SOURCE,
                        help=f"Directory sorgente (default: {DEFAULT_SOURCE})")
    parser.add_argument("--output", default=DEFAULT_OUTPUT,
                        help="Path del PDF di output")
    parser.add_argument("--no-http", action="store_true",
                        help="Salta la scansione HTTP live")
    parser.add_argument("--no-static", action="store_true",
                        help="Salta l'analisi statica del codice")
    args = parser.parse_args()

    _banner()
    print(f"  Target  : {args.target}")
    print(f"  Sorgente: {args.source}")
    print(f"  Output  : {args.output}\n")

    all_findings = []

    # 1. Analisi statica
    if not args.no_static:
        _progress("Analisi statica del codice sorgente...")
        static_findings = static.scan_directory(args.source)
        all_findings += static_findings
        print(f"       → {len(static_findings)} finding rilevati")

    # 2. Audit dipendenze
    _progress("Audit dipendenze (composer.lock, configurazione)...")
    dep_findings = deps.run_all(args.source)
    all_findings += dep_findings
    print(f"       → {len(dep_findings)} finding rilevati")

    # 3. Probe HTTP
    if not args.no_http:
        _progress(f"Scansione HTTP live su {args.target}...")
        http_findings = http_scan.run_all(args.target)
        all_findings += http_findings
        print(f"       → {len(http_findings)} finding rilevati")

    # Deduplicazione (stessa regola sullo stesso file+riga)
    seen = set()
    unique_findings = []
    for f in all_findings:
        key = (f.id, f.file, f.line)
        if key not in seen:
            seen.add(key)
            unique_findings.append(f)

    _summary(unique_findings)

    # 4. Genera PDF
    _progress(f"Generazione report PDF...")
    out = pdf_report.generate(
        findings=unique_findings,
        output_path=args.output,
        target=args.target,
        source=args.source,
    )
    print(f"  ✅ Report salvato: {out}\n")


if __name__ == "__main__":
    main()
