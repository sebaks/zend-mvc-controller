<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\ServiceFactory;
use T4webDomainInterface\ServiceInterface;

class ServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $service = $this->prophesize(ServiceInterface::class);

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('service')->willReturn('Some\Service');
        $serviceLocator->get('Some\Service')->willReturn($service->reveal());

        $factory = new ServiceFactory();

        $resultService = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($service->reveal(), $resultService);
    }
}
