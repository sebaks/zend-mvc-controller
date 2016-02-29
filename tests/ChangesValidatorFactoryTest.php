<?php

namespace Sebaks\ZendMvcControllerTest;

use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\ZendMvcController\ChangesValidatorFactory;
use Sebaks\Controller\ValidatorInterface;

class ChangesValidatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ServiceLocatorInterface::class);
        $app = $this->prophesize('\Zend\Mvc\Application');
        $event = $this->prophesize('Zend\Mvc\MvcEvent');
        $routeMatch = $this->prophesize('\Zend\Mvc\Router\Http\RouteMatch');
        $changesValidator = $this->prophesize(ValidatorInterface::class);

        $serviceLocator->get('Application')->willReturn($app->reveal());

        $event->getRouteMatch()->willReturn($routeMatch->reveal());

        $app->getMvcEvent()->willReturn($event->reveal());

        $routeMatch->getParam('changesValidator')->willReturn('Some\ChangesValidator');
        $serviceLocator->get('Some\ChangesValidator')->willReturn($changesValidator->reveal());

        $factory = new ChangesValidatorFactory();

        $service = $factory->createService($serviceLocator->reveal());

        $this->assertEquals($changesValidator->reveal(), $service);
    }
}
