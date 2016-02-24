<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\JsonModel;

class ApiViewModelFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('viewModel')) {
            $viewModel = $serviceLocator->get($routeMatch->getParam('viewModel'));
            if (! $viewModel instanceof JsonModel) {
                throw new \RuntimeException('ViewModel must be instance of ' . JsonModel::class);
            }
        } else {
            $viewModel = new JsonModel();
        }

        return $viewModel;
    }
}