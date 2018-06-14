<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Sebaks\Controller\ServiceInterface;
use Sebaks\Controller\EmptyService;

class ServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');
        /** @var \Zend\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('service')) {
            $service = $container->get($routeMatch->getParam('service'));
            if (!$service instanceof ServiceInterface) {
                throw new \RuntimeException('Service must be instance of ' . ServiceInterface::class);
            }
        } else {
            $service = new EmptyService();
        }

        return $service;
    }
}
