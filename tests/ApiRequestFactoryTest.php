<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\RequestInterface;
use Sebaks\ZendMvcController\ApiRequestFactory;

class ApiRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $zendRequest = $this->prophesize('\Zend\Http\PhpEnvironment\Request');
        $request = $this->prophesize(RequestInterface::class);

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $serviceLocator->get('request')->willReturn($zendRequest->reveal());

        $routeMatch->getParam('request')->willReturn('Some\Request');
        $serviceLocator->get('Some\Request')->willReturn($request->reveal());

        $zendRequest->getMethod()->willReturn('GET');
        $request->setMethod('GET')->willReturn(null);

        $factory = new ApiRequestFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(RequestInterface::class, $service);
    }
}
