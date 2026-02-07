#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

stamp="$(date +%Y%m%d-%H%M%S)"
out="mall-transfer-${stamp}.zip"

echo "Creating ${out} ..."

# IMPORTANT:
# - Use "." (not "*") so dotfiles like .htaccess are included.
# - Exclude big/dev-only directories and runtime caches/logs.
# - Keep storage/app/public + database sqlite (if used) to preserve uploads/data.
zip -r "${out}" . \
  -x "vendor/*" \
  -x "node_modules/*" \
  -x ".git/*" \
  -x ".env" \
  -x "storage/logs/*" \
  -x "storage/framework/cache/*" \
  -x "storage/framework/views/*" \
  -x "storage/framework/testing/*" \
  -x "bootstrap/cache/*" \
  -x "npm-debug.log*" \
  -x "yarn-error.log*" \
  -x "*.zip"

echo "Done: ${out}"
