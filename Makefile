.DEFAULT_GOAL := help

# Create method except for behat rule to avoid parsing next rules of queue
parse_cmd_args = $(filter-out $@,$(MAKECMDGOALS))

##
## Docker usage - from host machine
##-----------------------------------------------------------------
.PHONY: start stop

recreate: ## Recreate containers
	docker-compose up -d --force-recreate --remove-orphans

clean: ## Remove containers
	docker-compose down -v

start: ## Start containers
	docker-compose up -d

stop: ## Start containers
	docker-compose kill

connect: ## Connect to app container to run command manually
	docker-compose exec app sh

##
## Install
##-----------------------------------------------------------------
.PHONY: install backend frontend

init: install backend frontend ## Install app

install: ## Install dependencies
	composer install --no-interaction --no-scripts

backend: ## Prepare Backend
	tests/Application/bin/console sylius:install --no-interaction
	tests/Application/bin/console doctrine:database:create --if-not-exists -vvv
	tests/Application/bin/console doctrine:schema:update --complete --force --no-interaction -vvv
	tests/Application/bin/console cache:clear --env=test
	tests/Application/bin/console doctrine:database:create --env=test --if-not-exists -vvv
	tests/Application/bin/console doctrine:schema:update --env=test --complete --force --no-interaction -vvv
	tests/Application/bin/console sylius:fixtures:load --no-interaction
	chmod -Rf 777 tests/Application/public
	chmod -Rf 777 tests/Application/var
	mkdir -p tests/Application/var/cache/test/sessions
	chmod -Rf 777 tests/Application/var/cache/test/sessions

frontend: ## Prepare Frontend
	tests/Application/bin/console assets:install tests/Application/public
	(cd tests/Application && yarn install --pure-lockfile)
	(cd tests/Application && GULP_ENV=prod yarn build)

##
## Tests
##-----------------------------------------------------------------
.PHONY: phpspec phpunit behat

phpunit: ## Run unit tests
	vendor/bin/phpunit --colors=always $(call parse_cmd_args)

phpspec-ci: ## Run phpspec tests
	vendor/bin/phpspec run --ansi --no-interaction -f progress

phpspec: ## Run phpspec tests
	vendor/bin/phpspec run --ansi --no-interaction -f progress $(call parse_cmd_args)

behat-ci: ## Run functional tests
	APP_ENV=test vendor/bin/behat --profile docker --colors --strict --no-interaction -vvv -f progress

behat-js: ## Run only functional tests with Javascript support
	APP_ENV=test vendor/bin/behat --profile docker  --colors --strict --no-interaction -vvv -f progress --tags=@javascript $(call parse_cmd_args)

behat-no-js: ## Run only functional tests without Javascript support
	APP_ENV=test vendor/bin/behat --profile docker --colors --strict --no-interaction -vvv -f progress --tags=~@javascript $(call parse_cmd_args)

ci: init phpstan psalm phpunit-ci phpspec-ci behat-ci

integration: init phpunit-ci behat-ci

##
## QA
##-----------------------------------------------------------------
.PHONY: static phpstan psalm

static: install phpspec-ci phpstan psalm code-style validate normalize ## Run static analyses

phpstan: ## Run PHPStan analysis
	vendor/bin/phpstan analyse

psalm: ## Run Psalm analysis
	vendor/bin/psalm

validate: ## Validate composer.json
	composer validate --ansi --strict

normalize: ## Composer normalize
	composer normalize --dry-run --no-update-lock --no-check-lock

normalize-fix: ## Composer normalize
	composer normalize --no-update-lock --no-check-lock

lint: ## Run Lint task
	tests/Application/bin/console lint:twig templates tests/Application/templates
	tests/Application/bin/console lint:yaml config tests/Application/config

code-style: ## Run code style analysis
	vendor/bin/ecs check src spec features tests/Behat

code-style-fix: ## Run code style analysis
	vendor/bin/ecs check src spec features tests/Behat --fix

##
## Utilities
##-----------------------------------------------------------------
.PHONY: help

help: ## Show all make tasks (default)
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

-include Makefile.local
