#!/usr/bin/env bash

set -Eeuo pipefail

REPOSITORY_ROOT="${HOME}/public_html/spp"
LOCK_FILE="${HOME}/.spp-cpanel-sync.lock"

if command -v flock >/dev/null 2>&1; then
    exec 9>"${LOCK_FILE}"
    flock -n 9 || exit 0
fi

if [[ -x /usr/local/cpanel/bin/uapi ]]; then
    UAPI=/usr/local/cpanel/bin/uapi
elif [[ -x /usr/bin/uapi ]]; then
    UAPI=/usr/bin/uapi
else
    echo "ERROR: perintah UAPI cPanel tidak ditemukan."
    exit 1
fi

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Memeriksa pembaruan branch main..."

"${UAPI}" --output=json VersionControl update \
    repository_root="${REPOSITORY_ROOT}" \
    branch=main

# Pembaruan Git dikerjakan melalui antrean tugas cPanel. Jeda singkat ini
# memberi waktu agar HEAD terbaru tersedia sebelum deployment dibuat.
sleep 20

"${UAPI}" --output=json VersionControlDeployment create \
    repository_root="${REPOSITORY_ROOT}"

echo "[$(date '+%Y-%m-%d %H:%M:%S')] Permintaan deployment dikirim."
