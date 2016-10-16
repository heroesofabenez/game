#!/bin/bash
./vendor/bin/parallel-lint . -e php,phpt --exclude vendor --exclude temp
./vendor/bin/phpcs --extensions=php,phpt . --standard=cs-ruleset.xml --colors
php run_tests.php
