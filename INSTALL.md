Installation instructions
=========================

Requirements
------------

Obviously, you need PHP. Version 8.1 or later is required. Then you need web server (Apache is a safe bet but nginx or even PHP built-in server should be fine) and sql server (preferably MySql or MariaDb but PostgreSQL or MS SQL might be also used).
The game uses Composer to manage its dependencies, so you have to have it installed. You also need Git if you want to contribute.

Downloading
-----------

Clone the repository with git clone. Alternatively, you can download the source code from GitLab/GitHub in a archive.

Auto install
------------

After cloning the repository, you have to install the dependencies and create local configuration file and database with basic data. You can do that by hand if you wish but there is a script which will do that for you.
The script is called install.sh (yes, it is only for Unix-like systems). After running it you can skip to part Database.

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

The game uses Composer to manage its dependencies. If you do not have them installed, run *composer install* to obtain them. Sometimes, required/used versions of dependencies change so update them locally on regular basics with the same command.

Database
--------

The game needs a database to store its data. We use nextras/orm with nextras/dbal to access it which currently supports only MySQL/MariaDB, PostgreSQL and MS SQL. Before you can run the game for the first time, you have to create tables and fill them with at least basic data (test data are not strictly necessary if you do not run tests on that database). Folder app/sqls contains definitions of all tables and even basic and test data for MySql/MariaDb. So if you are using this server, just run these queries, and you are good to go. In the opposite case, tweak them accordingly before running.

After that, do not forget to write access data (name of database, username and password) to file app/config/local.neon so the game will know where to look for data.

Web server
----------

The game can run theoretically on any server but some might require more configuration than others. Below is the minimal required configuration to run the game on some popular servers, tested by the development team.

Whatever server you use, we strongly advise that the server name ends with .localhost, so it is considered a secure context by web browsers (that is required for some features).

### FrankenPHP

FrankenPHP is the recommended server as it was specifically created as a PHP app server and requires the least amount of configuration. In its configuration you only need to enable FrankenPHP, then use it for your server and define the root directory.

```
{
    frankenphp
}

hoa.localhost {
    root /var/www/html/heroesofabenez/www
    php_server
}
```

You will need to install a few PHP extensions: intl, xml and mysqli.

### Apache

If you're using Apache, you have little work to do as the repository contains all needed .htaccess files. Just set up a simple virtual host, no special configuration is needed.

Example of virtual host configuration:

```apacheconfig
<VirtualHost hoa.localhost:80>
    ServerName hoa.localhost
    DocumentRoot "/var/www/html/heroesofabenez/www"
</VirtualHost>
```

The document root for that virtual host (or its parent directory if it is withing /var/www/html) needs to have these settings:

```apacheconf
<Directory /var/www/html/heroesofabenez/www>
    AllowOverride All
    Require all granted
</Directory>
```

### Nginx

With nginx, you just need to add a new server configuration:

```nginx
server {
    listen 80;
    index index.php;
    server_name hoa.localhost;
    root /var/www/html/heroesofabenez/www;

    location / {
        try_files $uri /index.php?$args;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass localhost:9000;
        fastcgi_index index.php;
        rewrite_log on;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME /var/www/html/nexendrie/www/$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_param REMOTE_ADDR 127.0.0.1;
        fastcgi_pass_header Authorization;
    }
}
```

. Then you only need a running instance of php-fpm.

### Caddy

With Caddy, the setup is like with nginx (simple server configuration + php-fpm) but the server configuration is even simpler:

```
hoa.localhost {
    root * /var/www/html/heroesofabenez/www
    php_fastcgi php-fpm:9000
    file_server
}
```

. But it is better to use FrankenPHP which still runs Caddy under the hood and already contains everything needed to use PHP.

### PHP built-in server

If you do not want to bother with setting up and configuring a web server for development/testing, you can just use PHP built-in server. Just run this command:

```bash
php -S localhost:8080 -t www
```

### Other servers

If you have any other server, you (currently) have to do all server configuration by yourself as there are no experts on them in the development team. An important thing to have (configured) is something like mod_rewrite on Apache as we use "cool urls". If you have figured things out, please, tell us so we can update this section for other developers/testers who (consider to) use that server.
