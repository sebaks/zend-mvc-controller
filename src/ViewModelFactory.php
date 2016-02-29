<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;

class ViewModelFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('viewModel')) {
            $viewModel = $serviceLocator->get($routeMatch->getParam('viewModel'));
            if (! $viewModel instanceof ViewModel) {
                throw new \RuntimeException('ViewModel must be instance of ' . ViewModel::class);
            }
        } else {
            $viewModel = new ViewModel();
        }

        if (!$viewModel->getTemplate()) {
            $template = $routeMatch->getParam('template');
            if ($template) {
                if (!is_string($template)) {
                    throw new \RuntimeException('Parameter template must be string');
                }
                $viewModel->setTemplate($template);
            }
        }

        return $viewModel;
    }
}
