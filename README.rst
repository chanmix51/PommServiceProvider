
=============================
PommServiceProvider for Silex
=============================

This is the Pomm_ service provider for the Silex_ microframework. 

.. _Pomm: https://github.com/chanmix51/Pomm
.. _Silex: https://github.com/fabpot/Silex

Installation
------------

There are numerous ways to install *PommServiceProvider*. If you use git, use the submodules:

::

    $ mkdir vendor
    $ git submodule add git://github.com/chanmix51/PommServiceProvider.git vendor/GHub/Provider/Pomm 
    $ git submodule add git://github.com/chanmix51/Pomm.git vendor/pomm

Otherwise, you can just download the archives. The *PommServiceProvider*'s namespace is *GHub\\Provider\\Pomm**.

Using Pomm Service
-------------------

It is advised to split your application in 3 files:
bootstrap.php
    Where extensions are loaded and configured
application.php
    Where you define the code and bind it to the URLs.
generate_model.php
    The CLI tool to scan the postgresql's schemas and generate the model files.

This way, the *index.php* file is reduced to its simplest expression:

::

    <?php #index.php
    
    require(__DIR__.'/../application.php');
    
    $app->run();

The *application.php* itself is just composed by your controllers. It includes the configuration by requiring the *bootstrap.php* file:

::

    <?php #bootstrap.php

    require_once __DIR__.'/vendor/silex.phar';

    $app = new Silex\Application();

    // AUTOLOADING
    ...
    # pomm
    $app['autoloader']->registerNamespace('GHub', array(__DIR__.'/vendor'));
    $app['autoloader']->registerNamespace('Model', __DIR__);

    // EXTENSIONS
    ...
    # pomm
    $app->register(
        new PommServiceProvider(), 
        array(
            'pomm.class_path' => __DIR__.'/vendor/pomm', 
            'pomm.connections' => array(
                'default' => array(
                    'dsn' => 'pgsql://user:pass@host:port/dbname
                )))
            );

The *PommServiceProvider* class takes the following arguments: 

 - **pomm.class_path**: the path for the Pomm API that will be registered to the autoloader (optional)
 - **pomm.class_name**: This is the service class to be used (optional) default is Pomm\\Service. The given service class has to extend Pomm\\Service.
 - **pomm.connections**: an array of connections in the format 'name' => array('dsn' => $dsn, 'class' => 'My\\Database\\Class') 

The extension will instanciate the Database class for each of your connection which does not mean it will open connections. Connections are opened if you perform a statement in a connection. If you want to use your own Database class, provide its full path in the 'class' parameter. By default, this parameter is set to Pomm\\Connection\\Database.

In the example above, we register a *Model* namespace to the autoloader. I have used model files in *Model/Pomm* and generated files in *Model/Pomm/Map*.

Generating Map files
--------------------

Create the following script:

::

    <?php #generate_model.php

    require __DIR__.'/bootstrap.php';

    $scan = new Pomm\Tools\ScanSchemaTool(array(
        'schema' => 'YOUR SCHEMA',
        'connection' => $app['pomm']->getDatabase(),
        'prefix_dir' => __DIR__,
        ));
    $scan->execute();

