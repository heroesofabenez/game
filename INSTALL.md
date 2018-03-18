Installation instructions
=========================

Requirements
------------
Obviously, you need PHP. Version 7.2 or later is required. Then you need web server (preferably Apache or Nginx) and sql server (MySql, PgSql, MariaDb, etc.).
The game uses Composer to manage its dependencies so you have to have it installed. You also need Git if you want to contribute.

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
- /temp/cache
- /temp/sessions

. They are used to store generated data and they have to exist else you won't be able to run the application/use certain functions.

Local configuration
-------------------
After that, you need to create file /app/config/local.neon with local settings for database and application. Use app/config/local.sample.neon as template.

On live servers add the following lines at the end of the file:
```
application:
    errorPresenter: "Error"
    catchExceptions: true
hoa:
    userToCharacterMapper: HeroesofAbenez\Model\ProductionUserToCharacterMapper
```

. They enable our error handling and production handling of user accounts.

Dependencies
------------
The game uses Composer to manage its dependencies. If you do not have them installed, run *composer install* to obtain them. Then, you update them on regular basics with *composer update*.

Database
--------
The game needs a database to store its data. We use nextras/orm with nextras/dbal to access it which currently supports only MySQL/MariaDB and PostgreSQL. Before you can run the game for first time, you have to create tables and fill them with at least basic data. Folder app/sqls contains definitions of all table and even basic and test data for MySql/MariaDb. So if you are using this server, just run these queries and you are good to go.

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
If you have NGINX, you (currently) have to do all server configuration by yourself.
