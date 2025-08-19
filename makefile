.PHONY: build
build:
	docker build -f ./.docker/dockerfile -t weather .
