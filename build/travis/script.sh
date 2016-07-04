#! /bin/bash

set -x

originalDirectory=$(pwd)

cd ../phase3/tests/phpunit

php phpunit.php -c ../../extensions/SemanticCompoundQueries/
