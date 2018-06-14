<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Sebaks\Controller\Controller as SebaksController;

class ApiControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $criteriaValidator = $container->get('sebaks-zend-mvc-criteria-validator-factory');
        $changesValidator = $container->get('sebaks-zend-mvc-changes-validator-factory');
        $service = $container->get('sebaks-zend-mvc-service-factory');

        $viewModel = $container->get('sebaks-zend-mvc-api-view-model-factory');
        $request = $container->get('sebaks-zend-mvc-api-request-factory');
        $response = $container->get('sebaks-zend-mvc-response-factory');
        $error = $container->get('sebaks-zend-mvc-api-error-factory');
        $options = $container->get('sebaks-zend-mvc-options-factory');

        $sebaksController = new SebaksController($criteriaValidator, $changesValidator, $service);

        return new Controller($request, $response, $viewModel, $sebaksController, $error, $options);
    }
}
