.PHONY: generate-docs
.PHONY: check-style, test, test-all

generate-docs:
	./phpDocumentor.phar -d ./src -t ./docs

check-style:
	./vendor/bin/phpcs src tests

test:
	./vendor/bin/phpunit --coverage-text tests/

test-all: test check-style
