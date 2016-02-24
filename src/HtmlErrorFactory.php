<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HtmlErrorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        $viewModel = $serviceLocator->get('sebaks-zend-mvc-view-model-factory');

        return $error = new HtmlError($app->getMvcEvent(), $viewModel);
    }
}