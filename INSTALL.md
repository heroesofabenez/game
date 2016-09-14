Installation instructions
=========================

Requirements
------------
Obviously, you need PHP. Version 5.6 or later is required, but 7.0+ is highly recommended. Then you need web server (preferably Apache or Nginx) and sql server (MySql, PgSql, MariaDb, etc.).
The game uses Composer to manage its dependecies so you have to have it installed. You also need Git if you want to contribute.

Downloading
-----------
Clone the repository with git clone.

Auto install
------------

After cloning the repository, you have to install the dependencies and create certain folders, local configuration file and database with basic data. You can do that by hand if you wish but there is a script which will do that for you.
The scripts is called install.sh (yes, it is only for Unix-like systems). After running it you can skip to part Database.

Creating folders
----------------
Before you can start working (developing/testing) with the game, you have to create these empty folders:

- /images/maps
- /app/temp/cache
- /app/temp/sessions
- /app/log

. They are used to store generated data and they have to exist else you won't be able to run the application/use certain functions.

Local configuration
-------------------
After that, you need to create file /app/config/local.neon with local settings for database and application. Use app/config/local.sample.neon as template.

On live servers add the following lines at the end of the file:
```
application:
    errorPresenter: "Error"
    catchExceptions: true
```

. They enable our error handling.

Dependencies
------------
The game uses Composer to manage its dependencies. If you do not have them installed, run *composer install* to obtain them. Then, you update them on regular basics with *composer update*.

Database
--------
The game needs a database to store its data. We use nette/database to access it which is a layer above PDO so any database supported by it should be fine to use. Before you can run the game for first time, you have to create tables and fill the with at least basic data. Folder app/sqls contains definitions of all table and even basic and test data for MySql/MariaDb. So if you are using this server, just run these queries and you are good to go.

After that, do not forget to write access data (name of database, username and password) to file app/config/local.neon so the game will know where to look for data.

Web server
----------
### Apache
If you're using Apache, you have little work to do as the repository contains all needed .htaccess files. However with that configuration you would have to clone the repository to server's root. If you want to have it in different location, edit accordingly line

```
RewriteBase /
```

in /.htaccess and (optionally) set up a virtual host.
### NGINX
If you have NGINX, you (currenty) have to do all server configuration by yourself.
### General
It is adviced to install the game to server's root and use localhost, <yourcomputername>, or hoa.local as server's name. If you wish to use your computer's name or in general something not previously mentioned, add the name to app/config/local.neon to section hoa - devServers as element of array:

```
hoa:
    devServers:
        - kobliha
```

.

Additional resources
--------------------
When testing, you will also need to download definition of our coding standard. It can be found [here] (https://gitlab.com/heroesofabenez/resources/raw/master/cs-ruleset.xml).
