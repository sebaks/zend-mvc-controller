<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\ViewModelFactory;
use Zend\View\Model\ViewModel;

class ViewModelFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $viewModel = $this->prophesize(ViewModel::class);

        $viewModel->getTemplate()->willReturn('some-template');

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('viewModel')->willReturn('Some\ViewModel');
        $serviceLocator->get('Some\ViewModel')->willReturn($viewModel->reveal());

        $factory = new ViewModelFactory();

        $resultService = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($viewModel->reveal(), $resultService);
    }

    public function testCreateServiceWithCustomTemplate()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $viewModel = $this->prophesize(ViewModel::class);

        $viewModel->getTemplate()->willReturn(null);

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('viewModel')->willReturn('Some\ViewModel');
        $serviceLocator->get('Some\ViewModel')->willReturn($viewModel->reveal());
        $routeMatch->getParam('template')->willReturn('some-template');
        $viewModel->setTemplate('some-template')->willReturn(null);

        $factory = new ViewModelFactory();

        $resultService = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($viewModel->reveal(), $resultService);
    }
}
