#!/bin/sh
#npm install
#npm install bower
#./node_modules/.bin/bower install --allow-root
APP_VERSION=${1:-"0.1.2"} npm run dist
set -x
test -f /etc/redhat-release && SED="sed -i.bkp" || SED="sed -i .bkp"
# SED="sed -i.bkp"
#$SED "s|pace.min.js\"|pace.min.js\" data-pace-options='{ \"ajax\": false }'|g" 'dist/index.html'
$SED "s|@import url('main-blessed2.css');@import url('main-blessed1.css');||g" 'dist/css/main.css'
mv dist/index.html dist/frontend.php && rm -f dist/index.html.bkp
rm dist/css/main.css.bkp