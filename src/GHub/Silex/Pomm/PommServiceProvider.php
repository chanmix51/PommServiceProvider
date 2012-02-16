<?php

namespace GHub\Silex\Pomm;

use Silex\Application;
use Silex\ServiceProviderInterface;

class PommServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if (isset($app['pomm.class_path'])) {
            $app['autoloader']->registerNamespace('Pomm', $app['pomm.class_path']);
        }

        $app['pomm'] = $app->share(function() use ($app) {
            $class_name = isset($app['pomm.class_name']) ? $app['pomm.class_name'] : 'Pomm\Service';
            $service = new $class_name($app['pomm.databases']);

            return $service;
        });
    }
}
