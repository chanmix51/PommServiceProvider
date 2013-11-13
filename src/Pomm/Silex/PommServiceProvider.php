<?php

namespace Pomm\Silex;

use Silex\Application;
use Silex\ServiceProviderInterface;

class PommServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['pomm.class_name'] = 'Pomm\Service';
        $app['pomm'] = $app->share(function() use ($app) {
            $service = new $app['pomm.class_name']($app['pomm.databases']);

            return $service;
        });

        $app['pomm.connection'] = $app->share(function() use ($app) {
            return $app['pomm']->getDatabase()
                ->getConnection();
        });
    }

    public function boot(Application $app)
    {
    }
}
