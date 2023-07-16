#!/bin/bash

CONTAINER_NAME = champions-league-fpm
NETWORK_NAME = champions-league-network

HELP_FUN = \
	%help; \
    while(<>) { \
        if(/^([a-z0-9_-]+):.*\#\#(?:@(\w+))?\s(.*)$$/) { \
            push(@{$$help{$$2}}, [$$1, $$3]); \
        } \
    }; \
    print "\nusage: make [target]\n\n"; \
    for ( sort keys %help ) { \
        printf("  %-20s %s\n", $$_->[0], $$_->[1]) for @{$$help{$$_}}; \
        print "\n"; \
    }

setup:		## Prepare and run docker environment
	docker network create ${NETWORK_NAME} || true
	cp -n .env.exapmle .env || true
	docker-compose up -d

prepare: 	## Composer install
	docker exec -it ${CONTAINER_NAME} composer install || true
	 php artisan migrate --force
	 php artisan app:prepare-league-command

cc:  		## Cache clear
	docker exec -it ${CONTAINER_NAME} php artisan cache:clear

clear-log:  ## Clear log directory
	docker exec -it ${CONTAINER_NAME} rm -rf var/log/*

log:  		## Tail to logs
	docker exec -it ${CONTAINER_NAME} tail -f var/log/dev.log

bash:  		## Bash in the container
	docker exec -it ${CONTAINER_NAME} bash

help: 		## List commands with help
	@perl -e '$(HELP_FUN)' $(MAKEFILE_LIST)
