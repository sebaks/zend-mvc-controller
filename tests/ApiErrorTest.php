<?php

namespace Sebaks\ZendMvcControllerTest;

use Sebaks\ZendMvcController\ApiError;

class ApiErrorTest extends \PHPUnit_Framework_TestCase
{
    private $error;
    private $event;

    public function setUp()
    {
        $this->event = $this->prophesize('Zend\Mvc\MvcEvent');

        $this->error = new ApiError(
            $this->event->reveal()
        );
    }

    public function testMethodNotAllowed()
    {
        $zendResponse = $this->prophesize('Zend\Http\Response');
        $zendResponse->setStatusCode(405)->willReturn(null);

        $this->event->getResponse()->willReturn($zendResponse->reveal());

        $this->error->methodNotAllowed();
    }

    public function testNotFoundByRequestedCriteria()
    {
        $zendResponse = $this->prophesize('Zend\Http\Response');
        $zendResponse->setStatusCode(404)->willReturn(null);

        $this->event->getResponse()->willReturn($zendResponse->reveal());

        $this->error->notFoundByRequestedCriteria([]);
    }
}
