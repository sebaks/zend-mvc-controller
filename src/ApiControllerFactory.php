<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\Controller as SebaksController;

class ApiControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $serviceLocator = $controllerManager->getServiceLocator();

        $criteriaValidator = $serviceLocator->get('sebaks-zend-mvc-criteria-validator-factory');
        $changesValidator = $serviceLocator->get('sebaks-zend-mvc-changes-validator-factory');
        $service = $serviceLocator->get('sebaks-zend-mvc-service-factory');

        $viewModel = $serviceLocator->get('sebaks-zend-mvc-api-view-model-factory');
        $request = $serviceLocator->get('sebaks-zend-mvc-api-request-factory');
        $response = $serviceLocator->get('sebaks-zend-mvc-response-factory');
        $error = $serviceLocator->get('sebaks-zend-mvc-api-error-factory');
        $options = $serviceLocator->get('sebaks-zend-mvc-options-factory');

        $sebaksController = new SebaksController($criteriaValidator, $changesValidator, $service);

        return new Controller($request, $response, $viewModel, $sebaksController, $error, $options);
    }
}