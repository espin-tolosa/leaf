#!/bin/bash

./get_index.sh

while true; do
inotifywait -e modify,create,delete -r ../../../src && \
./get_index.sh

done
