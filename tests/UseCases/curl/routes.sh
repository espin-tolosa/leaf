#!/bin/bash

SCRIPT_DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )
source $SCRIPT_DIR/assert.sh

port=8000;
route="http://localhost";
auth="--header 'Cookie: jwt=sam'"

# Test Authorization Middleware

response=$(curl -s -I  --location --request GET 'http://localhost:4321/user/sam' | grep -o "401");
expected='401';
assert_eq "$expected" "$response" "Unauthorized request"

response=$(curl -s --location --request GET 'http://localhost:4321/user/sam' --header 'Cookie: jwt=sam' | grep -o 'Hello sam');
expected='Hello sam';
assert_eq "$expected" "$response" "Authorized request"

response=$(curl -s 'http://localhost:4321/public/spa.master.b26f2810.js' -H \
'Cookie: jwt=sam' | grep -o "Xa=function")
assert_eq "$response" "Xa=function" "public JS resource"

response=$(curl -I -s 'http://localhost:4321/' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:104.0) Gecko/20100101 Firefox/104.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate, br' -H 'Connection: keep-alive' -H 'Cookie: jwt=sam' -H 'Upgrade-Insecure-Requests: 1' -H 'Sec-Fetch-Dest: document' -H 'Sec-Fetch-Mode: navigate' -H 'Sec-Fetch-Site: cross-site' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache' | grep -o "200 OK")
assert_eq "$response" "200 OK" "Get Index"

response=$(curl -s 'http://localhost:4321/' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:104.0) Gecko/20100101 Firefox/104.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate, br' -H 'Connection: keep-alive' -H 'Cookie: jwt=sam' -H 'Upgrade-Insecure-Requests: 1' -H 'Sec-Fetch-Dest: document' -H 'Sec-Fetch-Mode: navigate' -H 'Sec-Fetch-Site: cross-site' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache' | grep -o "SPA")
assert_eq "$response" "SPA" "Get Index"

response=$(curl -I -s 'http://localhost:4321/not-found' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:104.0) Gecko/20100101 Firefox/104.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8' -H 'Accept-Language: en-US,en;q=0.5' -H 'Accept-Encoding: gzip, deflate, br' -H 'Connection: keep-alive' -H 'Cookie: jwt=sam' -H 'Upgrade-Insecure-Requests: 1' -H 'Sec-Fetch-Dest: document' -H 'Sec-Fetch-Mode: navigate' -H 'Sec-Fetch-Site: none' -H 'Sec-Fetch-User: ?1' -H 'Pragma: no-cache' -H 'Cache-Control: no-cache' | grep -o "404 Not Found")
assert_eq "$response" "404 Not Found" "Not Found View"