#!/bin/bash

PROJECT_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$PROJECT_ROOT" || exit 1

echo "=============================="
echo " CHECK DE SEGURANÇA - LARAVEL "
echo " Projeto: $PROJECT_ROOT"
echo "=============================="
echo ""

echo "1) Arquivos PHP modificados nos últimos 7 dias:"
find . -type f -name "*.php" -mtime -7
echo ""

echo "2) PHP em storage/ e public/ (NÃO deveria existir nada):"
find storage public -type f -name "*.php"
echo ""

echo "3) Busca por funções suspeitas:"
grep -R --line-number \
  --exclude-dir=vendor \
  --exclude-dir=storage/framework/views \
  -E "eval\(|base64_decode|gzinflate|shell_exec|passthru|system\(" \
  app bootstrap config database routes public
echo ""

echo "4) Arquivos sensíveis modificados nos últimos 30 dias:"
find . -maxdepth 2 -type f \( -name ".env" -o -name "index.php" -o -name ".htaccess" \) -mtime -30
echo ""

echo "5) Extensões suspeitas (.phtml, .phar):"
find . -type f \( -name "*.phtml" -o -name "*.phar" \)
echo ""

echo "=============================="
echo " FIM DO CHECK "
echo "=============================="
