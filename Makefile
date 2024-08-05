deps:
	composer install -o

check-permissions:
	@DIRECTORIES="./storage ./bootstrap/cache"; \
	for DIRECTORY in $$DIRECTORIES; do \
		if [ -d "$$DIRECTORY" ]; then \
			CURRENT_PERMISSIONS=$$(stat -c "%a" "$$DIRECTORY"); \
			[ "$$CURRENT_PERMISSIONS" -ne 777 ] && chmod -R 0777 "$$DIRECTORY" || true; \
		fi \
	done

dev: deps check-permissions
	docker-compose up --build
