.PHONY: help, generate-docs, tag
.PHONY: check-style, test, test-all

help: ## Show this help message
	@echo "Dotfiles v2 - Available targets:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

generate-docs: ## Generate docs
	./phpDocumentor.phar

install: ## Composer install
	composer install

OLD_VERSION = $(shell cat src/VERSION)
tag:
	@if [ "$(VERSION_PART)" = '' ]; then \
		echo "Must specify VERSION_PART to bump (major, minor, patch)."; \
		exit 1; \
	fi;
	./vendor/bin/bump_version bump --$(VERSION_PART)
	./scripts/tag_version.sh $(OLD_VERSION)

check-style: ## Run style checks
	./vendor/bin/phpcs src tests

test: ## Run tests with coverage
	./vendor/bin/phpunit --coverage-text tests/

test-all: test check-style ## Run tests and style checks
