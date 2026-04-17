"""
MCP Server per il database MySQL dell'applicazione UTC BIM.
Tutte le query sono in sola lettura (SELECT).
"""

import argparse
import os
import mysql.connector
from mysql.connector import Error
from mcp.server.fastmcp import FastMCP
from mcp.server.fastmcp.server import TransportSecuritySettings

# ── Argomenti CLI ──────────────────────────────────────────────────────────────

_parser = argparse.ArgumentParser(description="UTC BIM MCP Server", add_help=False)
_parser.add_argument("--transport", choices=["stdio", "sse", "streamable-http"],
                     default=os.getenv("MCP_TRANSPORT", "stdio"))
_parser.add_argument("--host", default=os.getenv("MCP_HOST", "0.0.0.0"))
_parser.add_argument("--port", type=int, default=int(os.getenv("MCP_PORT", "8765")))
_parser.add_argument("-h", "--help", action="store_true")
_args, _ = _parser.parse_known_args()

# ── Connessione ────────────────────────────────────────────────────────────────

DB_CONFIG = {
    "host":     os.getenv("DB_HOST",     "192.168.1.225"),
    "database": os.getenv("DB_NAME",     "utcbim"),
    "user":     os.getenv("DB_USER",     "utcuser"),
    "password": os.getenv("DB_PASSWORD", "Giorno16agosto$"),
    "charset":  "utf8mb4",
}


def get_conn():
    return mysql.connector.connect(**DB_CONFIG)


def query(sql: str, params: tuple = ()) -> list[dict]:
    conn = get_conn()
    try:
        cur = conn.cursor(dictionary=True)
        cur.execute(sql, params)
        return cur.fetchall()
    finally:
        conn.close()


# ── Server ─────────────────────────────────────────────────────────────────────

# In modalità rete disabilita la protezione DNS rebinding per consentire accesso LAN
_security = (
    TransportSecuritySettings(enable_dns_rebinding_protection=False)
    if _args.transport != "stdio"
    else None
)

mcp = FastMCP(
    "utcbim-db",
    host=_args.host,
    port=_args.port,
    transport_security=_security,
)


# ── Tool generici ──────────────────────────────────────────────────────────────

@mcp.tool()
def lista_tabelle() -> list[str]:
    """Restituisce l'elenco di tutte le tabelle del database utcbim."""
    rows = query("SHOW TABLES")
    return [list(r.values())[0] for r in rows]


@mcp.tool()
def descrivi_tabella(tabella: str) -> list[dict]:
    """
    Restituisce la struttura (colonne, tipo, nullable) di una tabella.
    Parametri:
      tabella: nome esatto della tabella
    """
    if not tabella.replace("_", "").isalnum():
        return [{"errore": "Nome tabella non valido"}]
    rows = query(f"DESCRIBE `{tabella}`")
    return rows


@mcp.tool()
def esegui_select(sql: str) -> list[dict] | dict:
    """
    Esegue una query SELECT libera sul database (sola lettura).
    La query deve iniziare con SELECT o WITH. Massimo 200 righe restituite.
    Parametri:
      sql: query SQL (es. "SELECT * FROM edilizia WHERE YEAR(DataProtocollo)=2024 LIMIT 10")
    """
    sql_clean = sql.strip().upper()
    if not (sql_clean.startswith("SELECT") or sql_clean.startswith("WITH")):
        return {"errore": "Sono consentite solo query SELECT o WITH."}
    if "LIMIT" not in sql_clean:
        sql = sql.rstrip("; ") + " LIMIT 200"
    try:
        return query(sql)
    except Error as e:
        return {"errore": str(e)}


# ── Tool specifici per dominio ─────────────────────────────────────────────────

@mcp.tool()
def pratiche_edilizia(
    anno: int | None = None,
    stato_id: int | None = None,
    limit: int = 20,
) -> list[dict]:
    """
    Elenca le pratiche edilizie con committente, titolo e stato.
    Parametri:
      anno:     filtra per anno del protocollo (es. 2024)
      stato_id: filtra per Stato_Pratica_id
      limit:    numero massimo di righe (default 20, max 100)
    """
    limit = min(limit, 100)
    conditions, params = [], []
    if anno:
        conditions.append("YEAR(e.DataProtocollo) = %s")
        params.append(anno)
    if stato_id is not None:
        conditions.append("e.Stato_Pratica_id = %s")
        params.append(stato_id)
    where = ("WHERE " + " AND ".join(conditions)) if conditions else ""
    sql = f"""
        SELECT
            e.edilizia_id,
            e.DataProtocollo,
            e.NumeroProtocollo,
            CONCAT(c.COGNOME, ' ', c.NOME) AS committente,
            t.descrizione                  AS titolo,
            se.descrizione                 AS stato,
            e.DescrizioneIntervento,
            e.IndirizzoImmobile
        FROM edilizia e
        LEFT JOIN committenti c     ON c.committenti_id = e.id_committente
        LEFT JOIN titoli_edilizia t ON t.titoli_id      = e.id_titolo
        LEFT JOIN stato_edilizia se ON se.stato_id      = e.Stato_Pratica_id
        {where}
        ORDER BY e.DataProtocollo DESC
        LIMIT %s
    """
    params.append(limit)
    try:
        return query(sql, tuple(params))
    except Error as e:
        return [{"errore": str(e)}]


@mcp.tool()
def pratiche_sismica(
    anno: int | None = None,
    stato: int | None = None,
    limit: int = 20,
) -> list[dict]:
    """
    Elenca le pratiche sismiche con committente e stato.
    Parametri:
      anno:  filtra per anno del protocollo
      stato: filtra per StatoPratica
      limit: numero massimo di righe (default 20, max 100)
    """
    limit = min(limit, 100)
    conditions, params = [], []
    if anno:
        conditions.append("YEAR(s.DataProtocollo) = %s")
        params.append(anno)
    if stato is not None:
        conditions.append("s.StatoPratica = %s")
        params.append(stato)
    where = ("WHERE " + " AND ".join(conditions)) if conditions else ""
    sql = f"""
        SELECT
            s.sismica_id,
            s.DataProtocollo,
            s.Protocollo,
            CONCAT(c.COGNOME, ' ', c.NOME) AS committente,
            s.DescrizioneLavori,
            s.Ubicazione,
            s.StatoPratica
        FROM sismica s
        LEFT JOIN committenti c ON c.committenti_id = s.committenti_id
        {where}
        ORDER BY s.DataProtocollo DESC
        LIMIT %s
    """
    params.append(limit)
    try:
        return query(sql, tuple(params))
    except Error as e:
        return [{"errore": str(e)}]


@mcp.tool()
def cerca_tecnico(nome: str) -> list[dict]:
    """
    Cerca un tecnico per nome o cognome (ricerca parziale).
    Parametri:
      nome: stringa da cercare in NOME o COGNOME
    """
    like = f"%{nome}%"
    sql = """
        SELECT tecnici_id, COGNOME, NOME, ALBO, PROVINCIA_ALBO,
               NUMERO_ISCRIZIONE, EMAIL, CELLULARE
        FROM tecnici
        WHERE COGNOME LIKE %s OR NOME LIKE %s
        ORDER BY COGNOME, NOME
        LIMIT 50
    """
    try:
        return query(sql, (like, like))
    except Error as e:
        return [{"errore": str(e)}]


@mcp.tool()
def cerca_committente(nome: str) -> list[dict]:
    """
    Cerca un committente per nome o cognome (ricerca parziale).
    Parametri:
      nome: stringa da cercare in NOME o COGNOME
    """
    like = f"%{nome}%"
    sql = """
        SELECT committenti_id, COGNOME, NOME, CODICE_FISCALE,
               INDIRIZZO, COMUNE_RESIDENZA, EMAIL
        FROM committenti
        WHERE COGNOME LIKE %s OR NOME LIKE %s
        ORDER BY COGNOME, NOME
        LIMIT 50
    """
    try:
        return query(sql, (like, like))
    except Error as e:
        return [{"errore": str(e)}]


@mcp.tool()
def statistiche_edilizia() -> dict:
    """
    Restituisce statistiche aggregate sulle pratiche edilizie:
    totale, per anno, per stato e per tipo di titolo.
    """
    try:
        totale     = query("SELECT COUNT(*) AS totale FROM edilizia")[0]["totale"]
        per_anno   = query("""
            SELECT YEAR(DataProtocollo) AS anno, COUNT(*) AS n
            FROM edilizia GROUP BY anno ORDER BY anno DESC LIMIT 10
        """)
        per_stato  = query("""
            SELECT se.descrizione AS stato, COUNT(*) AS n
            FROM edilizia e
            LEFT JOIN stato_edilizia se ON se.stato_id = e.Stato_Pratica_id
            GROUP BY stato ORDER BY n DESC
        """)
        per_titolo = query("""
            SELECT t.descrizione AS titolo, COUNT(*) AS n
            FROM edilizia e
            LEFT JOIN titoli_edilizia t ON t.titoli_id = e.id_titolo
            GROUP BY titolo ORDER BY n DESC
        """)
        return {
            "totale":     totale,
            "per_anno":   per_anno,
            "per_stato":  per_stato,
            "per_titolo": per_titolo,
        }
    except Error as e:
        return {"errore": str(e)}


@mcp.tool()
def oneri_concessori(anno: int | None = None) -> list[dict]:
    """
    Riepilogo degli oneri concessori per pratica edilizia.
    Parametri:
      anno: filtra per anno (opzionale)
    """
    conditions, params = [], []
    if anno:
        conditions.append("YEAR(e.DataProtocollo) = %s")
        params.append(anno)
    where = ("WHERE " + " AND ".join(conditions)) if conditions else ""
    sql = f"""
        SELECT
            e.edilizia_id,
            e.NumeroProtocollo,
            e.DataProtocollo,
            e.Oneri_Costruzione,
            e.Oneri_Urbanizzazione,
            e.Oneri_Pagati,
            (COALESCE(e.Oneri_Costruzione,0) + COALESCE(e.Oneri_Urbanizzazione,0)
             - COALESCE(e.Oneri_Pagati,0)) AS da_pagare
        FROM edilizia e
        {where}
        ORDER BY e.DataProtocollo DESC
        LIMIT 100
    """
    try:
        return query(sql, tuple(params))
    except Error as e:
        return [{"errore": str(e)}]


if __name__ == "__main__":
    if _args.help:
        _parser.print_help()
    else:
        mcp.run(transport=_args.transport)
