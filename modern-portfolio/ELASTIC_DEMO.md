# Elastic demo: instant portfolio search

This repo already has structured content in `db/portfolio.db` (`projects` table). This demo spins up **Elasticsearch + Kibana locally**, indexes those projects, and lets you run high-quality full-text search (plus filters/aggregations in Kibana).

## Run

From repo root:

```bash
chmod +x scripts/elastic/demo.sh
./scripts/elastic/demo.sh
```

## What you get

- **Search API**: `http://localhost:9200/portfolio-projects/_search`
- **Kibana UI**: `http://localhost:5601`

## Next step (optional)

If you like the search quality, the next “real” step is wiring a `/search.php?q=...` endpoint that calls Elasticsearch and displays results in your portfolio UI.

