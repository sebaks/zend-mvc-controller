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

        $this->assertEquals($request->reveal(), $service);
    }

    public function testCreateServiceWithDefaultRequestMultipartFormData()
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
        $routeMatch->getParams()->willReturn([]);

        $parameters = $this->prophesize('\Zend\Stdlib\ParametersInterface');
        $parameters->toArray()->willReturn([]);
        $zendRequest->getQuery()->willReturn($parameters->reveal());

        $contentType = $this->prophesize('\Zend\Http\Header\ContentType');
        $contentType->getMediaType()->willReturn('multipart/form-data');
        $headers = $this->prophesize('\Zend\Stdlib\ParametersInterface');
        $headers->get('contenttype')->willReturn($contentType->reveal());
        $zendRequest->getHeaders()->willReturn($headers->reveal());
        $zendRequest->getPost()->willReturn($parameters->reveal());
        $zendRequest->getFiles()->willReturn($parameters->reveal());

        $zendRequest->getMethod()->willReturn('GET');

        $factory = new ApiRequestFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(RequestInterface::class, $service);
    }

    public function testCreateServiceWithDefaultRequestJson()
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
        $routeMatch->getParams()->willReturn([]);

        $parameters = $this->prophesize('\Zend\Stdlib\ParametersInterface');
        $parameters->toArray()->willReturn([]);
        $zendRequest->getQuery()->willReturn($parameters->reveal());

        $contentType = $this->prophesize('\Zend\Http\Header\ContentType');
        $contentType->getMediaType()->willReturn('application/json');
        $headers = $this->prophesize('\Zend\Stdlib\ParametersInterface');
        $headers->get('contenttype')->willReturn($contentType->reveal());
        $zendRequest->getHeaders()->willReturn($headers->reveal());
        $zendRequest->getContent()->willReturn('{"json": true}');
        $zendRequest->getFiles()->willReturn($parameters->reveal());

        $zendRequest->getMethod()->willReturn('GET');

        $factory = new ApiRequestFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(RequestInterface::class, $service);
    }
}
