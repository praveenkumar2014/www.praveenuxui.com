#!/usr/bin/env bash
set -euo pipefail

ES_URL="${ES_URL:-http://localhost:9200}"
INDEX="${INDEX:-portfolio-projects}"
DB_PATH="${DB_PATH:-db/portfolio.db}"

if ! command -v docker >/dev/null 2>&1; then
  echo "docker is required" >&2
  exit 1
fi

if ! command -v curl >/dev/null 2>&1; then
  echo "curl is required" >&2
  exit 1
fi

if ! command -v php >/dev/null 2>&1; then
  echo "php is required" >&2
  exit 1
fi

echo "Starting Elasticsearch + Kibana..."
docker compose -f docker-compose.elastic.yml up -d

echo "Waiting for Elasticsearch at ${ES_URL}..."
for i in {1..90}; do
  if curl -fsS "${ES_URL}" >/dev/null 2>&1; then
    break
  fi
  sleep 1
done

if ! curl -fsS "${ES_URL}" >/dev/null 2>&1; then
  echo "Elasticsearch did not start in time." >&2
  exit 1
fi

echo "Creating index ${INDEX} (if missing)..."
curl -fsS -X PUT "${ES_URL}/${INDEX}" \
  -H 'Content-Type: application/json' \
  -d '{
    "settings": {
      "analysis": {
        "analyzer": {
          "default": { "type": "standard" }
        }
      }
    },
    "mappings": {
      "properties": {
        "id": { "type": "integer" },
        "title": { "type": "text", "fields": { "keyword": { "type": "keyword" } } },
        "category": { "type": "keyword" },
        "description": { "type": "text" },
        "created_at": { "type": "date", "ignore_malformed": true },
        "image": { "type": "keyword", "index": false },
        "link": { "type": "keyword", "index": false }
      }
    }
  }' >/dev/null 2>&1 || true

echo "Indexing from ${DB_PATH}..."
php scripts/elastic/index_projects_ndjson.php "${DB_PATH}" "${INDEX}" \
  | curl -fsS -X POST "${ES_URL}/_bulk?refresh=true" \
      -H 'Content-Type: application/x-ndjson' \
      --data-binary @- \
  | php -r '$r=json_decode(stream_get_contents(STDIN), true); if(!$r){fwrite(STDERR,"Bulk response not JSON\n"); exit(1);} if(!empty($r["errors"])){fwrite(STDERR,"Bulk had errors\n"); exit(1);} echo "Bulk indexed: ".($r["items"] ? count($r["items"]) : 0)."\n";'

echo
echo "Try a search (example: 'Agentic AI'):"
curl -fsS "${ES_URL}/${INDEX}/_search" \
  -H 'Content-Type: application/json' \
  -d '{"query":{"multi_match":{"query":"Agentic AI","fields":["title^3","category","description"]}},"size":5}' \
  | php -r '$r=json_decode(stream_get_contents(STDIN), true); foreach(($r["hits"]["hits"] ?? []) as $h){$s=$h["_source"]; echo "- ".$s["title"]." (".$s["category"].")\n";}'

echo
echo "Kibana is at http://localhost:5601 (create a Data View for index: ${INDEX})"

