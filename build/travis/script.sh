#! /bin/bash

set -x

originalDirectory=$(pwd)

cd ../phase3/tests/phpunit

php phpunit.php --group SemanticCompoundQueries -c ../../extensions/SemanticCompoundQueries/phpunit.xml.dist
