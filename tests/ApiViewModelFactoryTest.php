<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\ApiViewModelFactory;

class ApiViewModelFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $viewModel = $this->prophesize('Zend\View\Model\JsonModel');

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('viewModel')->willReturn('Some\ViewModel');
        $serviceLocator->get('Some\ViewModel')->willReturn($viewModel->reveal());

        $factory = new ApiViewModelFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf('Zend\View\Model\JsonModel', $service);
    }
}
