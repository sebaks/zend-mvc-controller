<?php

namespace Sebaks\ZendMvcController;

use Zend\Mvc\MvcEvent;

class ApiError implements ErrorInterface
{
    /**
     * @var MvcEvent
     */
    private $mvcEvent;

    /**
     * @param MvcEvent $mvcEvent
     */
    public function __construct(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;
    }

    /**
     * @return mixed
     */
    public function methodNotAllowed()
    {
        $zendResponse = $this->mvcEvent->getResponse();
        $zendResponse->setStatusCode(405);

        return $zendResponse;
    }

    /**
     * @return mixed
     */
    public function notFoundByRequestedCriteria()
    {
        $zendResponse = $this->mvcEvent->getResponse();
        $zendResponse->setStatusCode(404);

        return $zendResponse;
    }
}