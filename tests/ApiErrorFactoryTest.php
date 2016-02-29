<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\ApiError;
use Sebaks\ZendMvcController\ApiErrorFactory;

class ApiErrorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');

        $serviceLocator->get('Application')
            ->willReturn($app->reveal());

        $app->getMvcEvent()
            ->willReturn($this->prophesize('Zend\Mvc\MvcEvent')->reveal());

        $factory = new ApiErrorFactory();

        $controller = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(ApiError::class, $controller);
    }
}
