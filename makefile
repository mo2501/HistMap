all:
	@echo "Make sans paramètre est impossible"

dev:
	php bin/console assetic:dump
	php bin/console cache:clear --env=prod
	php bin/console cache:clear
	chmod -R 777 var/cache/ var/logs/ var/sessions

prod:
	php bin/console assetic:dump
	php bin/console cache:clear --env=prod
	php bin/console cache:clear
	chmod -R 777 var/cache/ var/logs/ var/sessions

mep:
	git pull origin master
	composer install