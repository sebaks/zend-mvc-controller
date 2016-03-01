<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\Request;

class RequestFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $serviceLocator->get('Application');
        /** @var \Zend\Mvc\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        /** @var \Zend\Http\PhpEnvironment\Request $zendRequest */
        $zendRequest = $serviceLocator->get('request');

        /** @var RequestInterface $request */
        if ($routeMatch->getParam('request')) {
            $request = $serviceLocator->get($routeMatch->getParam('request'));
            if (! $request instanceof RequestInterface) {
                throw new \RuntimeException('Request must be instance of ' . RequestInterface::class);
            }
        } else {
            $request = new Request();

            $routeMatchParams = $routeMatch->getParams();
            $routeMatchCriteria = [];
            if (!empty($routeMatchParams['routeCriteria'])) {
                if (is_string($routeMatchParams['routeCriteria'])) {
                    $routeMatchCriteria[$routeMatchParams['routeCriteria']] = $routeMatchParams[$routeMatchParams['routeCriteria']];
                }
                if (is_array($routeMatchParams['routeCriteria'])) {
                    foreach ($routeMatchParams['routeCriteria'] as $criteria) {
                        if (array_key_exists($criteria, $routeMatchParams)) {
                            $routeMatchCriteria[$criteria] = $routeMatchParams[$criteria];
                        }
                    }
                }
            }

            $criteria = array_merge($routeMatchCriteria, $zendRequest->getQuery()->toArray());
            $request->setCriteria($criteria);

            $changes = array_merge($zendRequest->getPost()->toArray(), $zendRequest->getFiles()->toArray());
            $request->setChanges($changes);
        }
        $request->setMethod($zendRequest->getMethod());

        return $request;
    }
}
