#!/bin/bash

# This script takes frontend and backend code, bundle it in a zip and send to
# the server he script can optionally deploy backend or frontend, but allways
# deploy configuration server files: .htaccess, robots.txt, unzip.php
#
# It is needed, because htaccess disallow php access so, it has to be deleted,
# call curl to unzip and deploy it again

deploy_backend=true
deploy_frontend=true

USER=`echo "amFtaG9sMAo=" | base64 --decode`
PASS=`echo "ZEhEMnYwaUcyKwo=" | base64 --decode`

VERSION=1.0

# ftp transfer
  lftp -u $USER,$PASS 162.210.101.174:21 << __EOF

  cd jhdiary.com
  put app.zip
  put unzip.php
  bye

__EOF

echo "Done";

#
#
# Unzip script from server
#  curl https://www.jhdiary.com/unzip.php
##
### Clean
##rm -rf app*
