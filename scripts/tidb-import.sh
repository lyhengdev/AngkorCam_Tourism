#!/bin/sh
set -e

SQL_FILE="${1:-database.sql}"

if [ ! -f "$SQL_FILE" ]; then
  echo "SQL file not found: $SQL_FILE" >&2
  exit 1
fi

if [ -z "${DB_HOST:-}" ] || [ -z "${DB_PORT:-}" ] || [ -z "${DB_USER:-}" ] || [ -z "${DB_NAME:-}" ]; then
  echo "Missing DB connection vars. Required: DB_HOST, DB_PORT, DB_USER, DB_NAME. Optional: DB_PASS, DB_SSL_CA." >&2
  exit 1
fi

SSL_OPTS=""
if [ -n "${DB_SSL_CA:-}" ]; then
  SSL_OPTS="--ssl-ca=${DB_SSL_CA}"
fi

echo "Importing ${SQL_FILE} into ${DB_NAME} on ${DB_HOST}:${DB_PORT}..."

if [ -n "${DB_PASS:-}" ]; then
  MYSQL_PWD="${DB_PASS}" mysql --protocol=TCP -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" ${SSL_OPTS} "${DB_NAME}" < "${SQL_FILE}"
else
  mysql --protocol=TCP -h "${DB_HOST}" -P "${DB_PORT}" -u "${DB_USER}" ${SSL_OPTS} "${DB_NAME}" < "${SQL_FILE}"
fi

echo "Import complete."
