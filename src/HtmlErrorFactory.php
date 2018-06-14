<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class HtmlErrorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');
        $viewModel = $container->get('sebaks-zend-mvc-view-model-factory');

        return $error = new HtmlError($app->getMvcEvent(), $viewModel);
    }
}
