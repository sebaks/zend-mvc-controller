<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\Request;

class ApiRequestFactory implements FactoryInterface
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
            unset($routeMatchParams['controller']);
            unset($routeMatchParams['allowedMethods']);
            unset($routeMatchParams['criteriaValidator']);
            unset($routeMatchParams['changesValidator']);
            unset($routeMatchParams['service']);
            unset($routeMatchParams['template']);
            unset($routeMatchParams['viewModel']);
            unset($routeMatchParams['redirectTo']);

            $criteria = array_merge($routeMatchParams, $zendRequest->getQuery()->toArray());
            $request->setCriteria($criteria);

            $changes = [];
            /** @var \Zend\Http\Header\ContentType $contentType */
            $contentType = $zendRequest->getHeaders()->get('contenttype');
            if ($contentType) {
                if ($contentType->getMediaType() == 'multipart/form-data') {
                    $changes = array_merge($zendRequest->getPost()->toArray(), $zendRequest->getFiles()->toArray());
                } elseif ($contentType->getMediaType() == 'application/json') {
                    $changes = array_merge(
                        (array)json_decode($zendRequest->getContent()),
                        $zendRequest->getFiles()->toArray()
                    );
                }
            }
            $request->setChanges($changes);
        }
        $request->setMethod($zendRequest->getMethod());

        return $request;
    }
}
