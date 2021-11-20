.PHONY: generate-docs, tag
.PHONY: check-style, test, test-all

generate-docs:
	./phpDocumentor.phar -d ./src -t ./docs

OLD_VERSION = $(shell cat src/VERSION)
tag:
	@if [ "$(VERSION_PART)" = '' ]; then \
		echo "Must specify VERSION_PART to bump (major, minor, patch)."; \
		exit 1; \
	fi;
	./vendor/bin/bump_version bump --$(VERSION_PART)
	./scripts/tag_version.sh $(OLD_VERSION)

check-style:
	./vendor/bin/phpcs src tests

test:
	./vendor/bin/phpunit --coverage-text tests/

test-all: test check-style
