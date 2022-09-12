#!/bin/bash

source assert.sh

port=8000;
route="http://localhost";
$auth = "--header 'Cookie: jwt=sam'"

clear

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
