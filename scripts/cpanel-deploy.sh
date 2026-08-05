#!/usr/bin/env bash

set -Eeuo pipefail

APP_PATH="${HOME}/public_html/spp"
DEPLOY_MARKER="${APP_PATH}/storage/app/.cpanel-deployed-sha"

cd "${APP_PATH}"

if [[ ! -f .env ]]; then
    echo "ERROR: ${APP_PATH}/.env belum tersedia. Buat melalui File Manager cPanel sebelum deployment."
    exit 1
fi

CURRENT_SHA="$(git rev-parse HEAD)"
if [[ -f "${DEPLOY_MARKER}" ]] && [[ "$(<"${DEPLOY_MARKER}")" == "${CURRENT_SHA}" ]]; then
    echo "Commit ${CURRENT_SHA} sudah pernah dideploy."
    exit 0
fi

PHP_BIN=""
for candidate in \
    /usr/local/bin/ea-php82 \
    /opt/cpanel/ea-php82/root/usr/bin/php \
    /usr/local/bin/php \
    /usr/bin/php
do
    if [[ -x "${candidate}" ]]; then
        PHP_BIN="${candidate}"
        break
    fi
done

if [[ -z "${PHP_BIN}" ]]; then
    echo "ERROR: PHP CLI 8.2 tidak ditemukan. Hubungi penyedia hosting untuk path PHP CLI."
    exit 1
fi

COMPOSER_BIN=""
for candidate in \
    /usr/local/bin/composer \
    /opt/cpanel/composer/bin/composer \
    /usr/bin/composer
do
    if [[ -f "${candidate}" || -x "${candidate}" ]]; then
        COMPOSER_BIN="${candidate}"
        break
    fi
done

if [[ -z "${COMPOSER_BIN}" ]]; then
    echo "ERROR: Composer tidak ditemukan. Minta penyedia hosting mengaktifkan Composer untuk akun cPanel."
    exit 1
fi

mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chmod -R ug+rwX storage bootstrap/cache

"${PHP_BIN}" "${COMPOSER_BIN}" install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

if command -v npm >/dev/null 2>&1; then
    npm ci --no-audit --no-fund
    npm run build
else
    echo "PERINGATAN: Node.js/NPM tidak tersedia; build Vite dilewati."
fi

"${PHP_BIN}" artisan optimize:clear
"${PHP_BIN}" artisan migrate --force

if [[ ! -e public/storage ]]; then
    "${PHP_BIN}" artisan storage:link
fi

"${PHP_BIN}" artisan optimize

printf '%s\n' "${CURRENT_SHA}" > "${DEPLOY_MARKER}"
echo "Deployment commit ${CURRENT_SHA} selesai."
