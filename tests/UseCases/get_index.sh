#!/bin/bash

port=8000;
route="http://localhost";

# Test Security over XSS Injection
xss_request="${route}:${port}/?name=%22%3E%3Cbr%3E%3Cinput%20type=%22text%22%20id=%22xss%22%20placeholder=%22password%22%3E%3Cscript%3EsetTimeout(()=%3E{window.alert(document.getElementById(%22xss%22).value)},5000)%3C/script%3E"

bad_response="\"><br><input type=\"text\" id=\"xss\" placeholder=\"password\"><script>setTimeout(()=>window.alert(document.getElementById(\"xss\").value),5000)</script>";

clear
response=$(curl -s $xss_request)

if [ "$response" == "$bad_response" ]; then
  echo "XSS Injected";
else
  echo "Prevention against XSS OK"
fi

