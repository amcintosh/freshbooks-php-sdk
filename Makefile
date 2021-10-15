.PHONY: check-style, test


check-style:
	./vendor/bin/phpcs src tests

test:
	./vendor/bin/phpunit --coverage-text tests/
