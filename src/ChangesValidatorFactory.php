<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\ValidatorInterface;
use Sebaks\Controller\EmptyValidator;

class ChangesValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('changesValidator')) {
            $changesValidator = $serviceLocator->get($routeMatch->getParam('changesValidator'));
            if (! $changesValidator instanceof ValidatorInterface) {
                throw new \RuntimeException('Changes validator must be instance of ' . ValidatorInterface::class);
            }
        } else {
            $changesValidator = new EmptyValidator();
        }

        return $changesValidator;
    }
}