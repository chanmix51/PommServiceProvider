<?php

namespace Pomm\Silex\Tests;

use Silex\Application;
use Pomm\Silex\PommServiceProvider;

class PommServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testServiceDeclaration()
    {
        $app = new Application();

        $app->register(new PommServiceProvider(), array(
            'pomm.databases' => array(
                'default' => array('dsn' => 'pgsql://user:pass@host:port/dbname')
            ),
        ));

        $this->assertInstanceOf('Pomm\\Service', $app['pomm']);
    }
}
