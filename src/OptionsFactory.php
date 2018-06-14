<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class OptionsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');
        /** @var \Zend\Router\Http\RouteMatch $routeMatch */
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
