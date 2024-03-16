run-tailwind:
	php bin/console tailwind:build --watch &

run-server:
	symfony server:start

run:
	$(MAKE) run-tailwind
	$(MAKE) run-server
.PHONY: run
