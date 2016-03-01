<?php

namespace Sebaks\ZendMvcControllerTest;

use Sebaks\ZendMvcController\HtmlError;

class HtmlErrorTest extends \PHPUnit_Framework_TestCase
{
    private $viewModel;
    private $error;
    private $event;

    public function setUp()
    {
        $this->event = $this->prophesize('Zend\Mvc\MvcEvent');
        $this->viewModel = $this->prophesize('Zend\View\Model\ViewModel');

        $this->error = new HtmlError(
            $this->event->reveal(),
            $this->viewModel->reveal()
        );
    }

    public function testMethodNotAllowed()
    {
        $zendResponse = $this->prophesize('Zend\Http\Response');
        $zendResponse->setStatusCode(405)->willReturn(null);

        $this->viewModel->setVariable('message', 'The requested method not allowed')->willReturn(null);
        $this->viewModel->setTemplate('error/404')->willReturn(null);

        $this->event->setResult($this->viewModel->reveal())->willReturn(null);

        $this->event->getResponse()->willReturn($zendResponse->reveal());

        $this->error->methodNotAllowed();
    }

    public function testNotFoundByRequestedCriteria()
    {
        $zendResponse = $this->prophesize('Zend\Http\Response');
        $zendResponse->setStatusCode(404)->willReturn(null);

        $this->viewModel->setVariable(
            'message',
            'The requested resource was not found by requested criteria'
        )->willReturn(null);
        $this->viewModel->setTemplate('error/404')->willReturn(null);

        $this->event->setResult($this->viewModel->reveal())->willReturn(null);

        $this->event->getResponse()->willReturn($zendResponse->reveal());

        $this->error->notFoundByRequestedCriteria();
    }
}
