deps:
	composer install -o

.PHONY: check-permissions
check-permissions:
	@DIRECTORIES="./storage ./bootstrap/cache"; \
	for DIRECTORY in $$DIRECTORIES; do \
		if [ -d "$$DIRECTORY" ]; then \
			CURRENT_PERMISSIONS=$$(stat -c "%a" "$$DIRECTORY"); \
			[ "$$CURRENT_PERMISSIONS" -ne 777 ] && chmod -R 0777 "$$DIRECTORY"; \
		fi \
	done

dev: deps check-permissions
	composer install -o
	docker-compose up --build
