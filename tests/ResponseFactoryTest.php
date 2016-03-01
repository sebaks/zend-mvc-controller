<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\ResponseFactory;
use Sebaks\Controller\ResponseInterface;

class ResponseFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $response = $this->prophesize(ResponseInterface::class);

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('response')->willReturn('Some\Response');
        $serviceLocator->get('Some\Response')->willReturn($response->reveal());

        $factory = new ResponseFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($response->reveal(), $service);
    }
}
