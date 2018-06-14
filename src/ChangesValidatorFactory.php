<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Sebaks\Controller\ValidatorInterface;
use Sebaks\Controller\EmptyValidator;

class ChangesValidatorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');
        /** @var \Zend\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('changesValidator')) {
            $changesValidator = $container->get($routeMatch->getParam('changesValidator'));
            if (! $changesValidator instanceof ValidatorInterface) {
                throw new \RuntimeException('Changes validator must be instance of ' . ValidatorInterface::class);
            }
        } else {
            $changesValidator = new EmptyValidator();
        }

        return $changesValidator;
    }
}
