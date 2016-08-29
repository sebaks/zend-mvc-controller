<?php

namespace Sebaks\ZendMvcControllerTest;

use Sebaks\ZendMvcController\ApiError;
use Zend\Http\Headers;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;

class ApiErrorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ApiError
     */
    private $error;

    public function setUp()
    {
        $response = new Response();
        $response->setHeaders(new Headers());
        $mvcEvent = new MvcEvent();
        $mvcEvent->setResponse($response);
        $this->error = new ApiError($mvcEvent);
    }

    public function testMethodNotAllowed()
    {
        $zendResponse = $this->error->methodNotAllowed();

        $this->assertEquals(405, $zendResponse->getStatusCode());
    }

    public function testNotFoundByRequestedCriteria()
    {
        $content = ['some' => 'error'];

        $zendResponse = $this->error->notFoundByRequestedCriteria($content);

        $this->assertEquals(404, $zendResponse->getStatusCode());
        $this->assertEquals('Content-Type', $zendResponse->getHeaders()->get('Content-Type')->getFieldName());
        $this->assertEquals('application/json', $zendResponse->getHeaders()->get('Content-Type')->getFieldValue());
        $this->assertEquals(json_encode($content), $zendResponse->getContent());
    }
}
