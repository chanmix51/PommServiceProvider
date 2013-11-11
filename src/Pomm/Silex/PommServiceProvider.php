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

        $app['pomm.logger'] = $app->share(function() {
            return new \Pomm\Tools\Logger();
        });

        $app['pomm.connection'] = $app->share(function() use ($app) {
            $connection = $app['pomm']->getDatabase()
                ->getConnection();

            if ($app['debug'] === true and isset($app['pomm.logger']))
            {
                $connection->registerFilter(new \Pomm\FilterChain\LoggerFilter($app['pomm.logger']));
            }

            return $connection;
        });
    }

    public function boot(Application $app)
    {
    }
}
