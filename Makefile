# Makefile for running PHP tests

.DEFAULT_GOAL := test

# Variables
PHPUNIT = vendor/bin/phpunit
FILTER ?= 

# Colors for output
GREEN = \033[0;32m
NC = \033[0m # No Color

# Targets
.PHONY: install
install: ## Install PHP dependencies via Composer
	@echo "$(GREEN)Installing dependencies...$(NC)"
	@composer install

.PHONY: help
help: ## Show this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(GREEN)%-15s$(NC) %s\n", $$1, $$2}'

.PHONY: test
test: ## Run all tests
	@echo "$(GREEN)Running all tests...$(NC)"
	@$(PHPUNIT)

.PHONY: test-coverage
test-coverage: ## Run tests with coverage report
	@echo "$(GREEN)Running tests with coverage report...$(NC)"
	@$(PHPUNIT) --coverage-html=coverage

.PHONY: test-filter
test-filter: ## Run tests matching a pattern (e.g., make test-filter FILTER=ClassName)
	@if [ -z "$(FILTER)" ]; then \
		echo "Please specify a filter: make test-filter FILTER=TestName"; \
		exit 1; \
	fi
	@echo "$(GREEN)Running tests matching pattern: $(FILTER)$(NC)"
	@$(PHPUNIT) --filter="$(FILTER)"

.PHONY: test-verbose
test-verbose: ## Run tests in verbose mode
	@echo "$(GREEN)Running tests in verbose mode...$(NC)"
	@$(PHPUNIT) --verbose

.PHONY: test-stop-on-failure
test-stop-on-failure: ## Run tests and stop on first failure
	@echo "$(GREEN)Running tests (stop on first failure)...$(NC)"
	@$(PHPUNIT) --stop-on-failure

