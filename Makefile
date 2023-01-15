# Makefile

BIND_ADDR ?= 0.0.0.0
BIND_PORT ?= 8080

## help: Display this help message.
help:
	@echo
	@echo "Available targets:"
	@echo
	@echo TODO
	@echo
.PHONY: help

## init: Initialize the project.
init:
	@ ./scripts/init.sh
.PHONY: init

## run: Run the built-in php web server.
run:
	@ echo "--> Serving from www on $(ADDRESS)"
	@ echo
	@ php artisan serve --host=$(BIND_ADDR) --port=$(BIND_PORT)
.PHONY: run

## db-reset: Stop, destroy, create and start the database.
db-reset: db-teardown db-bootstrap

## db-bootstrap: Create, start, migrate and seed the database.
db-bootstrap: db-create db-start db-migrate db-seed

## db-teardown: Stop and destroy the database.
db-teardown: db-stop db-destroy

## db-create: Create a podman container for the database.
db-create:
	@ echo "--> Creating database container"
	podman create \
		-p 5432:5432 \
		--name ecommerce-db \
		-e POSTGRES_DB=ecommerce \
		-e POSTGRES_HOST_AUTH_METHOD=trust \
		postgres:latest
.PHONY: db-create

## db-destroy: Destroy the database container.
db-destroy:
	@ echo "--> Destroying database"
	podman rm -f ecommerce-db
.PHONY: db-destroy

## db-start: Start the database container.
db-start:
	@ echo "--> Starting database"
	podman start ecommerce-db
	@ echo "--> Waiting 5 seconds for database to initialize"
	@ sleep 5
.PHONY: db-start

## db-stop: Stop the database container.
db-stop:
	@ echo "--> Stopping database"
	podman stop ecommerce-db
.PHONY: db-stop

## db-seed: Seed the database.
db-seed:
	@ echo "--> Seeding database"
	php artisan db:seed
.PHONY: db-seed

## db-migrate: Migrate the database.
db-migrate:
	@ echo "--> Migrating database"
	php artisan migrate
.PHONY: db-migrate
