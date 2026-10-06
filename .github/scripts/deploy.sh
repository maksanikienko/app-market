#!/usr/bin/env bash
# Runs on the server: activates an uploaded release with zero downtime.
# Usage: deploy.sh <app_dir> <release_name>
set -euo pipefail

APP_DIR="$1"
RELEASE="$2"
KEEP_RELEASES="${KEEP_RELEASES:-5}"

RELEASE_DIR="$APP_DIR/releases/$RELEASE"
SHARED_DIR="$APP_DIR/shared"
ARCHIVE="$RELEASE_DIR.tar.gz"

[ -f "$SHARED_DIR/.env" ] || { echo "Missing $SHARED_DIR/.env — create it before the first deploy" >&2; exit 1; }

mkdir -p "$RELEASE_DIR"
tar -xzf "$ARCHIVE" -C "$RELEASE_DIR" --exclude=./storage
tar -xzf "$ARCHIVE" -C "$SHARED_DIR" --skip-old-files ./storage
rm "$ARCHIVE"

ln -s "$SHARED_DIR/.env" "$RELEASE_DIR/.env"
ln -s "$SHARED_DIR/storage" "$RELEASE_DIR/storage"

cd "$RELEASE_DIR"
php artisan storage:link --force
php artisan migrate --force
php artisan optimize

ln -sfn "$RELEASE_DIR" "$APP_DIR/current.tmp"
mv -Tf "$APP_DIR/current.tmp" "$APP_DIR/current"

php artisan queue:restart
[ -z "${PHP_FPM_SERVICE:-}" ] || sudo systemctl reload "$PHP_FPM_SERVICE"

ls -1dt "$APP_DIR"/releases/*/ | tail -n +$((KEEP_RELEASES + 1)) | xargs -r rm -rf

echo "Deployed $RELEASE"
