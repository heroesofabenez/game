echo 'parameters:
    application:
        server: sTest
database:
    default:
        dsn: "mysql:host=localhost;dbname=heroesofabenez"
        user: heroesofabenez
        password: heroesofabenez
        explain: false
tracy:
    email: "jakub.konecny2@centrum.cz"
php:
    date.timezone: Europe/Prague' > app/config/local.neon

mkdir images
mkdir images/maps
mkdir app/temp
mkdir app/temp/cache
mkdir app/temp/sessions
mkdir app/log

composer install

exit 0
