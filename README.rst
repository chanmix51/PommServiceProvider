
=============================
PommServiceProvider for Silex
=============================

This is the Pomm_ service provider for the Silex_ microframework.

.. _Pomm: https://github.com/chanmix51/Pomm
.. _Silex: https://github.com/fabpot/Silex

Installation
------------

There are numerous ways to install *PommServiceProvider*.

Composer
********

`Composer <http://packagist.org/packages/pomm/pomm-service-provider>`_ is the easiest way to get the Service provider installed and running. Just add your ``composer.json`` the following::

    "pomm/pomm-service-provider":    "master-dev"

in the ``require`` section. Invoke Â«``composer.phar install``Â» and it should be installed with the ``Pomm`` library.

Git submodules
**************
If you use git, use the submodules:

::

    $ mkdir vendor
    $ git submodule add git://github.com/chanmix51/PommServiceProvider.git vendor/ghub/PommServiceProvider
    $ git submodule add git://github.com/chanmix51/Pomm.git vendor/pomm

Otherwise, you can just download the archive, expand it in a subdirectory and tell the autoloader that ``GHub\Silex\Pomm`` namespace is under the ``src`` project subdirectory.

Using Pomm Service
-------------------

It is advised to split your application in 3 files:

bootstrap.php
    Where extensions are loaded and configured
application.php
    Where you define your controllers.
generate_model.php
    The CLI tool to scan the postgresql's schemas and generate the model files.

This way, the *index.php* file is reduced to its simplest expression:

::

    <?php #index.php
    
    require(__DIR__.'/../application.php');
    
    $app->run();

The ``application.php`` itself is just composed by your controllers. It includes the configuration by requiring the ``bootstrap.php`` file:

::

    <?php #bootstrap.php

    require_once __DIR__.'/vendor/autoload.php';

    $app = new Silex\Application();

    // EXTENSIONS
    ...
    # pomm
    $app->register(
        new Pomm\Silex\PommServiceProvider(),
        array(
            'pomm.class_path' => __DIR__.'/vendor/pomm',
            'pomm.databases' => array(
                'default' => array(
                    'dsn' => 'pgsql://user:pass@host:port/dbname',
                )))
            );

The *PommServiceProvider* class takes the following arguments:

 - **pomm.class_name**: This is the service class to be used (optional) default is ``Pomm\Service``. The given service class has to extend ``Pomm\Service``.
 - **pomm.databases**: an array of databases in the format ``'name' => array('dsn' => $dsn, 'class' => 'My\\Database\\Class')``.

The extension will instanciate the Database class for each of your connection which does not mean it will open connections. Connections are opened if you perform a statement in a connection. If you want to use your own Database class, provide its full path in the ``class`` parameter. By default, this parameter is set to ``Pomm\Connection\Database``.

In the example above, we register a ``Model`` namespace to the autoloader so generated files would be places in ``Model\DbName\SchemaName`` namespace.

Generating Map files
--------------------

Create the following script:

::

    <?php #generate_model.php

    require __DIR__.'/bootstrap.php';

    $scan = new Pomm\Tools\ScanSchemaTool(array(
        'schema' => 'YOUR SCHEMA',
        'database' => $app['pomm']->getDatabase(),
        'prefix_dir' => __DIR__.'/Model',
        ));
    $scan->execute();

