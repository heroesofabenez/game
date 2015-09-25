Installation instructions
=========================

Downloading
-----------
The game now uses Composer to manage its dependencies so you have to
clone/fork the repository and then run Composer to get the dependencies.

Creating folders
----------------
Before you can start working (developing/testing) with the game, you have to
create these empty folders:

- /images/maps
- /temp
- /temp/cache
- /temp/sessions
- /app/log

. They are used to store generated data and they have to exist else you won't 
be able to run the application/use certain functions.

Local configuration
-------------------
After that, you need to create file /app/config/local.neon with local settings 
for database and application. The needed minimum is (just an example):

```
parameters:
    application:
        server: sTest
database:
    default:
        dsn: "mysql:host=localhost;dbname=heroesofabenez"
        user: heroesofabenez
        password: qwerty
tracy:
    email: "jakub.konecny2@centrum.cz"
php:
    date.timezone: Europe/Prague
```

On live servers add the following lines at the end of the file:
```
application:
    errorPresenter: "Error"
    catchExceptions: true
```

. They enable our error handling.

Web server
----------
### Apache
If you're using Apache, you have little work to do as the repository contains
all needed .htaccess files. However with that configuration you would have to
clone the repository to /heroesofabenez. If you want to have it in different
location, edit accordingly line
RewriteBase /heroesofabenez
in /.htaccess and (optionally) set up a virtual host.
### NGINX
If you have NGINX, you (currenty) have to do all server configuration by yourself.
### General
It is adviced to install the game to server's root and use localhost, 
<yourcomputername>, or hoa.local as server's name. If you wish to use your
computer's name or in general something not previously mentioned, add the name to app/config/local.neon to section model - devServers as element of array:

```
hoa.model:
    devServers:
        - kobliha
```
.