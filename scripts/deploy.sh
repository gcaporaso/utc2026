#!/bin/bash
# =============================================================================
# deploy.sh — Aggiornamento semi-automatico del server di produzione
#
# Uso: ./scripts/deploy.sh
#
# Cosa fa:
#   1. Esegue git push su GitHub dal server di sviluppo
#   2. Si collega via SSH al server di produzione (100.113.118.24)
#   3. Backup automatico del codice e del database su prod
#   4. git pull da GitHub (preservando i file di configurazione locali)
#   5. Corregge i permessi
#   6. Svuota la cache Yii2
# =============================================================================

set -e

PROD_HOST="giuseppe@100.113.118.24"
PROD_DIR="/var/www/utcbim"
BACKUP_DIR="/home/giuseppe/backups_pre_deploy"
TIMESTAMP=$(date '+%Y%m%d_%H%M%S')

# File di configurazione da NON sovrascrivere su prod
PROTECTED_FILES=(
    "config/db.php"
    "config/params.php"
    "config/web.php"
    "web/index.php"
)

# ---------------------------------------------------------------------------
echo ""
echo "========================================================"
echo "  DEPLOY UTC-BIM — $(date '+%d/%m/%Y %H:%M:%S')"
echo "========================================================"
echo ""

# ---------------------------------------------------------------------------
# 1. Push su GitHub dal server di sviluppo
# ---------------------------------------------------------------------------
echo "[1/5] Push su GitHub..."
git -C "$PROD_DIR/../utcbim" push origin main 2>&1 || {
    # Se siamo già in /var/www/utcbim
    git push origin main 2>&1
}
echo "      OK"

# ---------------------------------------------------------------------------
# 2. Backup su produzione (codice + database)
# ---------------------------------------------------------------------------
echo "[2/5] Backup su produzione..."
ssh "$PROD_HOST" "
    set -e
    mkdir -p $BACKUP_DIR

    # Backup codice (escludi runtime e vendor)
    tar -czf $BACKUP_DIR/code_$TIMESTAMP.tar.gz \
        --exclude='$PROD_DIR/runtime' \
        --exclude='$PROD_DIR/vendor' \
        --exclude='$PROD_DIR/web/assets' \
        -C /var/www utcbim 2>/dev/null || true
    CODE_SIZE=\$(du -h $BACKUP_DIR/code_$TIMESTAMP.tar.gz 2>/dev/null | cut -f1 || echo 'n/d')
    echo \"      Codice: \$CODE_SIZE\"

    # Backup database
    DB_HOST=\$(php -r \"
        \\\$c = require '$PROD_DIR/config/db.php';
        preg_match('/host=([^;]+)/', \\\$c['dsn'], \\\$m);
        echo \\\$m[1] ?? '127.0.0.1';
    \")
    DB_NAME=\$(php -r \"
        \\\$c = require '$PROD_DIR/config/db.php';
        preg_match('/dbname=([^;]+)/', \\\$c['dsn'], \\\$m);
        echo \\\$m[1] ?? '';
    \")
    DB_USER=\$(php -r \"\\\$c = require '$PROD_DIR/config/db.php'; echo \\\$c['username'];\")
    DB_PASS=\$(php -r \"\\\$c = require '$PROD_DIR/config/db.php'; echo \\\$c['password'];\")

    OPTFILE=\$(mktemp)
    printf '[client]\npassword=%s\n' \"\$DB_PASS\" > \"\$OPTFILE\"
    chmod 600 \"\$OPTFILE\"
    mysqldump --defaults-extra-file=\"\$OPTFILE\" -h \"\$DB_HOST\" -u \"\$DB_USER\" \
        --single-transaction --routines --triggers \"\$DB_NAME\" | \
        gzip > $BACKUP_DIR/db_$TIMESTAMP.sql.gz
    rm -f \"\$OPTFILE\"
    DB_SIZE=\$(du -h $BACKUP_DIR/db_$TIMESTAMP.sql.gz 2>/dev/null | cut -f1 || echo 'n/d')
    echo \"      Database: \$DB_SIZE\"

    # Mantieni solo gli ultimi 10 backup
    ls -t $BACKUP_DIR/code_*.tar.gz 2>/dev/null | tail -n +11 | xargs rm -f
    ls -t $BACKUP_DIR/db_*.sql.gz   2>/dev/null | tail -n +11 | xargs rm -f
"
echo "      OK"

# ---------------------------------------------------------------------------
# 3. Aggiornamento codice su produzione
# ---------------------------------------------------------------------------
echo "[3/5] Aggiornamento codice da GitHub..."
ssh "$PROD_HOST" "
    set -e
    cd $PROD_DIR

    # Salva i file di configurazione locali
    TMPDIR=\$(mktemp -d)
    cp config/db.php     \$TMPDIR/db.php     2>/dev/null || true
    cp config/params.php \$TMPDIR/params.php 2>/dev/null || true
    cp config/web.php    \$TMPDIR/web.php    2>/dev/null || true
    cp web/index.php     \$TMPDIR/index.php  2>/dev/null || true

    # Corregge permessi prima del reset (alcuni file potrebbero essere di www-data)
    sudo chown -R giuseppe:www-data $PROD_DIR
    sudo find $PROD_DIR -type f -exec chmod 664 {} \;
    sudo find $PROD_DIR -type d -exec chmod 775 {} \;

    # Fetch e reset al branch main remoto
    git fetch origin main
    git reset --hard origin/main

    # Ripristina i file di configurazione locali
    cp \$TMPDIR/db.php     config/db.php     2>/dev/null || true
    cp \$TMPDIR/params.php config/params.php 2>/dev/null || true
    cp \$TMPDIR/web.php    config/web.php    2>/dev/null || true
    cp \$TMPDIR/index.php  web/index.php     2>/dev/null || true
    rm -rf \$TMPDIR

    echo '      Commit attivo: '\$(git log --oneline -1)
"
echo "      OK"

# ---------------------------------------------------------------------------
# 4. Migrazione database
# ---------------------------------------------------------------------------
echo "[4/6] Migrazione database..."
ssh "$PROD_HOST" "
    set -e
    cd $PROD_DIR

    DB_HOST=\$(php -r \"\\\$c = require 'config/db.php'; preg_match('/host=([^;]+)/', \\\$c['dsn'], \\\$m); echo \\\$m[1] ?? '127.0.0.1';\")
    DB_NAME=\$(php -r \"\\\$c = require 'config/db.php'; preg_match('/dbname=([^;]+)/', \\\$c['dsn'], \\\$m); echo \\\$m[1] ?? '';\")
    DB_USER=\$(php -r \"\\\$c = require 'config/db.php'; echo \\\$c['username'];\")
    DB_PASS=\$(php -r \"\\\$c = require 'config/db.php'; echo \\\$c['password'];\")

    OPTFILE=\$(mktemp)
    printf '[client]\npassword=%s\n' \"\$DB_PASS\" > \"\$OPTFILE\"
    chmod 600 \"\$OPTFILE\"
    mysql --defaults-extra-file=\"\$OPTFILE\" -h \"\$DB_HOST\" -u \"\$DB_USER\" \"\$DB_NAME\" < scripts/migrate_gis.sql
    rm -f \"\$OPTFILE\"
    echo '      Tabelle GIS create/verificate'
"
echo "      OK"

# ---------------------------------------------------------------------------
# 5. Composer install
# ---------------------------------------------------------------------------
echo "[5/6] Composer install..."
ssh "$PROD_HOST" "
    set -e
    cd $PROD_DIR
    composer install --no-dev --no-interaction --optimize-autoloader 2>&1 | tail -5
"
echo "      OK"

# ---------------------------------------------------------------------------
# 6. Permessi
# ---------------------------------------------------------------------------
echo "[6/6] Aggiornamento permessi..."
ssh "$PROD_HOST" "
    sudo chown -R giuseppe:www-data $PROD_DIR
    sudo find $PROD_DIR -type d -exec chmod 755 {} \;
    sudo find $PROD_DIR -type f -exec chmod 644 {} \;
    sudo chmod -R 777 $PROD_DIR/runtime
    sudo chmod -R 777 $PROD_DIR/web/assets
    sudo chmod 755 $PROD_DIR/yii
" 2>&1 || echo "      (permessi: eseguire manualmente con sudo se necessario)"
echo "      OK"

# ---------------------------------------------------------------------------
# 5. Svuota cache Yii2
# ---------------------------------------------------------------------------
echo "[6/6] Pulizia cache..."
ssh "$PROD_HOST" "
    cd $PROD_DIR
    php yii cache/flush-all 2>/dev/null || true
    rm -rf $PROD_DIR/runtime/cache/* 2>/dev/null || true
"
echo "      OK"

# ---------------------------------------------------------------------------
echo ""
echo "========================================================"
echo "  DEPLOY COMPLETATO CON SUCCESSO"
echo "  Backup salvato: $BACKUP_DIR/code_$TIMESTAMP.tar.gz"
echo "========================================================"
echo ""
