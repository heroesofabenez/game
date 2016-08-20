cp app/config/local.sample.neon app/config/local.neon

mkdir images
mkdir images/maps
mkdir app/temp
mkdir app/temp/cache
mkdir app/temp/sessions
mkdir app/log

composer install -a

exit 0
