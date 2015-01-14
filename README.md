EDeploy
================================

EDeploy is simple deploy application for shared hosting.


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      components/         contains application components
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      migrations/         contains db migrations
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this application template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

### Framework and dependencies

If you do not have Composer, you may install it by following the instructions at getcomposer.org.
You can then install this application template using the following command:

~~~
composer global require "fxp/composer-asset-plugin:1.0.0-beta3"
composer install
~~~


### Configs

Copy `db.back.php` and `params.back.php` without `.back` to config directory and adjust to your needs.
Change `securityKey` in `params.php`.


### Database

Create a database. By this moment you should have `config/db.php`. Specify your database connection there.
Then apply migrations by running:

~~~
yii migrate
~~~

