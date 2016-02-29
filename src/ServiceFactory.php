<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\ServiceInterface;
use Sebaks\Controller\EmptyService;

class ServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('service')) {
            $service = $serviceLocator->get($routeMatch->getParam('service'));
            if (!$service instanceof ServiceInterface) {
                throw new \RuntimeException('Service must be instance of ' . ServiceInterface::class);
            }
        } else {
            $service = new EmptyService();
        }

        return $service;
    }
}
