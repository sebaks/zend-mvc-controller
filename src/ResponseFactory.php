<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Sebaks\Controller\ResponseInterface;
use Sebaks\Controller\Response;

class ResponseFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');
        /** @var \Zend\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        if ($routeMatch->getParam('response')) {
            $response = $container->get($routeMatch->getParam('response'));
            if (! $response instanceof ResponseInterface) {
                throw new \RuntimeException('Response must be instance of ' . ResponseInterface::class);
            }
        } else {
            $response = new Response();
        }

        return $response;
    }
}
