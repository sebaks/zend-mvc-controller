<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        $options = [];
        $options['allowedMethods'] = $routeMatch->getParam('allowedMethods', []);
        if (!empty($options['allowedMethods']) && !is_array($options['allowedMethods'])) {
            throw new \RuntimeException('Parameter allowedMethods must be array');
        }

        $options['redirectTo'] = $routeMatch->getParam('redirectTo');
        if (!empty($options['redirectTo']) && !is_string($options['redirectTo'])) {
            throw new \RuntimeException('Parameter redirectTo must be string');
        }

        return $options;
    }
}