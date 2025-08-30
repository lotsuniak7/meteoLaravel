.PHONY: build
build:
	docker build -f ./.docker/dockerfile -t weather .

.PHONY: up
up:
	docker compose -f .docker/compose.yml down
	docker compose -f .docker/compose.yml up --build --remove-orphans -d