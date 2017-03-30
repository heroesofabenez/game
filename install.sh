#!/bin/bash
cp app/config/local.sample.neon app/config/local.neon

mkdir images
mkdir images/maps
mkdir temp/cache
mkdir temp/sessions

composer install

exit 0
