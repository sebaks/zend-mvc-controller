<?php

namespace Sebaks\ZendMvcController;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\Request;

class ApiRequestFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var \Zend\Mvc\Application $app */
        $app = $container->get('Application');
        /** @var \Zend\Router\Http\RouteMatch $routeMatch */
        $routeMatch = $app->getMvcEvent()->getRouteMatch();

        /** @var \Zend\Http\PhpEnvironment\Request $zendRequest */
        $zendRequest = $container->get('request');

        /** @var RequestInterface $request */
        if ($routeMatch->getParam('request')) {
            $request = $container->get($routeMatch->getParam('request'));
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
            if ($zendRequest->isPost()) {
                /** @var \Zend\Http\Header\ContentType $contentType */
                $contentType = $zendRequest->getHeaders()->get('content-type');
                if ($contentType) {
                    if ($contentType->getMediaType() == 'multipart/form-data') {
                        $changes = array_merge($zendRequest->getPost()->toArray(), $zendRequest->getFiles()->toArray());
                    } elseif ($contentType->getMediaType() == 'application/json') {
                        $changes = array_merge(
                            json_decode($zendRequest->getContent(), true),
                            $zendRequest->getFiles()->toArray()
                        );
                    }
                }
            }
            $request->setChanges($changes);
        }
        $request->setMethod($zendRequest->getMethod());

        return $request;
    }
}
