ROOT_DIR:=$(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))
VERSION:=$(shell git describe --tags)
VERSION_ESCAPED:=$(subst .,\.,$(VERSION))

requirements = var \
	vendor \
	node_modules \
	web/main.css \
	phpunit.xml

build: $(requirements)
	./vendor/bin/phpcs --extensions=php --standard=app/standard/Clean -s src/
	./vendor/bin/phpmd src/ text app/standard/phpmd.xml
	sed -i '' -e 's/version: .*$$/version: $(VERSION_ESCAPED)/g' app/config/parameters.yml
	php70 bin/console cache:clear -e dev
	php70 bin/console cache:clear -e prod
	php70 bin/console doctrine:migrations:migrate --no-interaction
	make test

web/main.css: node_modules
	cp node_modules/spectre.css/dist/spectre.min.css web/main.css
	touch web/main.css

test:
	php70 ./vendor/bin/phpunit -c .

migrations:
	php70 bin/console doctrine:migrations:diff --no-interaction

node_modules: package.json
	npm install
	touch node_modules

vendor: composer.lock
	composer install
	touch vendor

composer.lock: composer.json
	composer update

var:
	mkdir var
	chmod -R 0777 var

phpunit.xml: phpunit.xml.dist
	cp phpunit.xml.dist phpunit.xml

system:
	sudo puppet apply --modulepath=./system system.pp

.PHONY: system build js