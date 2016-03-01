<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\HtmlErrorFactory;
use Sebaks\ZendMvcController\ErrorInterface;

class HtmlErrorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $viewModel = $this->prophesize('Zend\View\Model\ModelInterface');

        $serviceLocator->get('Application')->willReturn($app->reveal());
        $serviceLocator->get('sebaks-zend-mvc-view-model-factory')->willReturn($viewModel->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $factory = new HtmlErrorFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertInstanceOf(ErrorInterface::class, $service);
    }
}
