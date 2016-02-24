<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\ValidatorInterface;
use Sebaks\Controller\EmptyValidator;

class CriteriaValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('criteriaValidator')) {
            $criteriaValidator = $serviceLocator->get($routeMatch->getParam('criteriaValidator'));
            if (! $criteriaValidator instanceof ValidatorInterface) {
                throw new \RuntimeException('Criteria validator must be instance of ' . ValidatorInterface::class);
            }
        } else {
            $criteriaValidator = new EmptyValidator();
        }

        return $criteriaValidator;
    }
}