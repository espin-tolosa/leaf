#!/bin/bash

./vendor/bin/phpunit
./tests/UseCases/curl/routes.sh

while true; do
inotifywait -e modify,create,delete -r . && \
clear
./vendor/bin/phpunit
./tests/UseCases/curl/routes.sh
done
