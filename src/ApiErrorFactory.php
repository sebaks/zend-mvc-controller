<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class ApiErrorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');

        return $error = new ApiError($app->getMvcEvent());
    }
}
