# Changelog modifiche al codice

Documento che riassume tutte le modifiche apportate al progetto utcbim.

---

## Sessione di sviluppo (marzo–aprile 2026)

### 1. Integrazione MinosX — Pubblica Illuminazione

**Obiettivo:** Visualizzare su mappa le posizioni e gli stati dei pali e delle cabine elettriche del sistema di pubblica illuminazione gestito da MinosX (UMPI), tramite proxy server-side.

#### `config/params.php`
- Aggiunti parametri di connessione al sistema MinosX:
  - `minoxUrl` — URL base di minosxcloud.umpi.it
  - `minoxUser` / `minoxPassword` — credenziali di accesso
  - `minoxDb` — nome database (`mx_campoli`)

#### `controllers/MappeController.php`
- Aggiunta action `actionMinosxLamps()`:
  - Autentica al sistema MinosX via cURL con cookie jar (sessione)
  - Seleziona il database del comune
  - Recupera lampade (`read_lamps`) o cabine (`read_cabinets`) dalla `function_map.php`
  - Costruisce la mappatura `lamp_id → nome quadro` tramite `jqueryFileTree.php` (una chiamata per ogni cabina)
  - Converte le coordinate da EPSG:3857 a WGS84
  - Aggiunge la proprietà `QUADRO` a ogni lampada
  - Restituisce GeoJSON pulito al client

#### `web/mappe/js/minosx.js` *(file nuovo)*
- Dizionario `MINOSX_STATUS_COLOR`: colori per stato lampada (campo `STATUS`)
- Dizionario `MINOSX_STATUS_LABEL`: etichette testuali per stato
- Dizionario `MINOSX_FAILURE_LABELS`: mapping codice `FAILURE` → descrizione stato:
  - `0301` → Stato non acquisito
  - `0302` → Lampada OK
  - `0305` → Dimming non eseguito
  - (altri codici da completare man mano che si verificano)
- `_minoxLampStyle(status)`: stile cerchio per lampade (colore da STATUS)
- `_minoxCabinetIcon(status)`: icona quadrata 14×14 px (DivIcon) per cabine
- `_minoxLampPopup(props)`: popup lampada con badge stato, riga "Stato" (da FAILURE), riga "Quadro"
- `_minoxCabinetPopup(props)`: popup cabina con badge stato
- `_getMinoxLampsLayer()` / `_getMinoxCabinetsLayer()`: layer GeoJSON con lazy loading su evento `add`
- `buildMinosxTreeNode()`: nodo per `L.Control.Layers.Tree`

#### `views/mappe/mappe.php`
- Aggiunta variabile JS `MINOSX_PROXY_URL` generata con `Url::to(['mappe/minosx-lamps'])`
- Registrazione di `minosx.js`

#### `web/mappe/js/layers.js`
- Aggiunto `buildMinosxTreeNode()` come nodo figlio dell'`overlaysTree`

---

### 2. Backup e ripristino database

**Obiettivo:** Sostituire il pacchetto `beaten-sect0r/yii2-db-manager` (incompatibile con PHP 8.4 + Codeception 5.x) con una soluzione custom basata su `mysqldump`.

#### `controllers/DbBackupController.php` *(file nuovo)*
- `actionIndex()`: lista i backup disponibili in `runtime/backups/`, ordina per data decrescente, legge il log dei ripristini
- `actionCreate()`: esegue `mysqldump` con `--defaults-extra-file` (password in file temporaneo, non visibile nel process list), comprime con `gzip`, salva in `runtime/backups/`
- `actionRestore()`:
  1. Crea automaticamente un backup di sicurezza (`_pre_restore_`) del database corrente
  2. Esegue `gunzip | mysql` per il ripristino
  3. Registra l'operazione nel log `restore_log.json` (timestamp, file ripristinato, backup di sicurezza, utente)
- `actionDownload()`: invia il file `.sql.gz` al browser
- `actionDelete()`: elimina un file di backup
- Helper `_dbParams()`: legge host/port/dbname/user/password dalla configurazione Yii
- Helper `_writeOptFile()`: crea file temporaneo MySQL options (chmod 0600)
- Helper `_appendRestoreLog()` / `_readRestoreLog()`: gestisce `restore_log.json`
- Accesso: solo utenti autenticati (`roles => ['@']`)

#### `views/db-backup/index.php` *(file nuovo)*
- Tabella backup disponibili con azioni: ripristina, scarica, elimina
- Badge "sicurezza" sui file `_pre_restore_`
- Modale di conferma ripristino con avviso sul backup automatico di sicurezza
- Tabella cronologia ripristini da `restore_log.json`

#### `views/layouts/sidebar.php`
- Aggiornato link voce di menu "Backup" da `/db-manager/default/index` a `/db-backup/index`

---

### 3. Stampa mappa in scala (A3 landscape)

**Obiettivo:** Permettere la stampa della mappa in formato A3 orizzontale con scala cartografica selezionabile.

#### `views/mappe/mappe.php`
- CSS `@media print`:
  - Formato A3 landscape (`size: A3 landscape`)
  - `visibility: hidden` su `body *`, `visibility: visible` su `#mapid` e contenuto
  - Selettore `#mapid .leaflet-control-container` (specificità ID+classe) per nascondere i controlli in stampa
  - Div `#print-scale-label` visibile solo in stampa (rosso, grassetto, angolo in basso a sinistra)
- Modale Bootstrap `#modal-print` con select per scala (1:500 → 1:25000)
- Div `#print-waiting-overlay` per feedback durante il caricamento tiles

#### `web/mappe/js/controlli.js`
- `PrintControl`: `L.Control.extend` con pulsante stampa, apre `#modal-print`
- `L.control.scale({ imperial: false })` aggiunto permanentemente alla mappa
- `printMap(map, scaleDenom)`:
  - Calcola lo zoom target dalla formula cartografica: `zoom = log2(156543.034 × cos(lat) × 96 / (0.0254 × scaleDenom))`
  - Ridimensiona il container a 1587×1122 px (A3 a 96 dpi, nessuna scalatura)
  - Conta le tiles tramite eventi Leaflet (`tileloadstart` / `tileload` / `tilerror`)
  - Chiama `window.print()` quando `pending === 0`; fallback timeout 4 s
  - Evento `afterprint`: scrive etichetta scala, ripristina dimensioni container, zoom e centro mappa

---

### 4. Stili CTR 2020

**Obiettivo:** Applicare la palette cromatica ufficiale della CTR Campania 1:5000 a tutti i layer GeoJSON.

#### `web/mappe/js/ctr2020.js`
- Sostituiti gli stili generici con tre dizionari di lookup:
  - `_CTR_LIN_STYLES`: 60+ tipi di linea (strade, corsi d'acqua, recinzioni, ecc.)
  - `_CTR_POL_STYLES`: 70+ tipi di poligono (edifici, vegetazione, strade, acque, ecc.)
    - Esempio: `'Edificio civile'`: bordo rosso spessore 1.8, riempimento arancio-marrone `#c87d3e`
  - `_CTR_POI_STYLES`: 35+ tipi di punto
- Funzione `style()` aggiornata per usare i dizionari con fallback al colore di default per layer

---

### 5. Layer base "Nessuna" (disattivazione satellite)

**Obiettivo:** Aggiungere l'opzione di rimuovere il layer Google Satellite di base.

#### `views/mappe/mappe.php`
- Aggiunto oggetto `{ label: 'Nessuna', layer: L.gridLayer() }` all'array dei layer base
- `L.gridLayer()` è un layer vuoto trasparente che consente di visualizzare la mappa senza sfondo

---

## Note tecniche

- **Autenticazione MinosX**: non esiste un'API pubblica REST; il proxy utilizza l'autenticazione a sessione cookie (`curl` con cookie jar) replicando il flusso del browser.
- **Mapping QUADRO**: il campo `LINE` nei dati lamp non identifica univocamente la cabina; il proxy chiama `jqueryFileTree.php` per ogni cabina per ottenere i lamp ID figli.
- **Password mysqldump**: passata tramite `--defaults-extra-file` (file temp chmod 0600) per evitare che appaia nel process list.
- **Stampa Leaflet**: il ridimensionamento del container prima della stampa evita la sfocatura causata da `transform: scale()`; il conteggio eventi tile garantisce che tutte le tiles siano caricate prima di `window.print()`.
- **URL proxy**: `enablePrettyUrl` non è attivo; l'URL corretto è generato con `Url::to(['mappe/minosx-lamps'])` → `index.php?r=mappe%2Fminosx-lamps`.
