## Setup

To get it working, follow these steps:

**Download Composer dependencies**
```
composer install
```

You may alternatively need to run `php composer.phar install`, depending on how you installed Composer.

**Start the Symfony web server**

You can use Nginx or Apache, but Symfony's local web server is also an option.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found here: https://symfony.com/download - you only need to do this once
on your system.

Then, to start the web server, open a terminal, move into the project, and run to start server in daemon mode:

```
symfony server:start -d
```

(If this is your first time using this command, you may see an error that you need to run `symfony server:ca:install`
first).

**Dealing with database**
Run
```shell
docker-compose up
```
to start local Postgres instance. In project directory run in the terminal
```shell
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
```
to upgrade database to the project current scheme and populate it with dev data.

**Optional: Webpack Encore Assets**

If you *do* want to build the Webpack Encore assets manually
```
yarn install
yarn encore dev --watch
```
