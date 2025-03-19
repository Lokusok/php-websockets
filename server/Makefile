setup:
	docker compose up --build -d
	sqlite3 "$(CURDIR)/database.db" < "$(CURDIR)/migration.sql"

start:
	docker compose exec php php ./src/server.php
