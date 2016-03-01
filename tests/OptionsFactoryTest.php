<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\OptionsFactory;

class OptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $options = [
            'allowedMethods' => ['GET', 'POST'],
            'redirectTo' => 'home',
        ];
        $routeMatch->getParam('allowedMethods', [])->willReturn($options['allowedMethods']);
        $routeMatch->getParam('redirectTo')->willReturn($options['redirectTo']);

        $factory = new OptionsFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($options, $service);
    }
}
