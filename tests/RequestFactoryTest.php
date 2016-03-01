<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\RequestFactory;
use Sebaks\Controller\RequestInterface;

class RequestFactoryTest extends \PHPUnit_Framework_TestCase
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
        $serviceLocator->get('request')->willReturn($zendRequest->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('request')->willReturn('Some\Request');
        $serviceLocator->get('Some\Request')->willReturn($request->reveal());

        $zendRequest->getMethod()->willReturn('GET');
        $request->setMethod('GET')->willReturn(null);

        $factory = new RequestFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($request->reveal(), $service);
    }

    public function testCreateServiceWithDefaultRequest()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $zendRequest = $this->prophesize('\Zend\Http\PhpEnvironment\Request');

        $serviceLocator->get('Application')->willReturn($app->reveal());
        $serviceLocator->get('request')->willReturn($zendRequest->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('request')->willReturn(null);
        $routeMatch->getParams()->willReturn([
            'id' => '1',
            'routeCriteria' => 'id',
        ]);

        $parameters = $this->prophesize('\Zend\Stdlib\ParametersInterface');
        $parameters->toArray()->willReturn([]);
        $zendRequest->getQuery()->willReturn($parameters->reveal());
        $zendRequest->getPost()->willReturn($parameters->reveal());
        $zendRequest->getFiles()->willReturn($parameters->reveal());

        $zendRequest->getMethod()->willReturn('GET');

        $factory = new RequestFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(RequestInterface::class, $service);
        $this->assertEquals(['id' => '1'], $service->getCriteria());
    }
}
