<?php

namespace Sebaks\ZendMvcControllerTest;

use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\ResponseInterface;
use Sebaks\Controller\Controller as SebaksController;
use Sebaks\ZendMvcController\Controller;
use Sebaks\ZendMvcController\ErrorInterface;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    private $request;
    private $response;
    private $viewModel;
    private $error;
    private $event;
    private $controller;

    public function setUp()
    {
        $this->request = $this->prophesize(RequestInterface::class);
        $this->response = $this->prophesize(ResponseInterface::class);
        $this->viewModel = $this->prophesize('Zend\View\Model\ViewModel');
        $sebaksController = $this->prophesize(SebaksController::class);
        $this->error = $this->prophesize(ErrorInterface::class);
        $this->event = $this->prophesize('Zend\Mvc\MvcEvent');

        $this->controller = new Controller(
            $this->request->reveal(),
            $this->response->reveal(),
            $this->viewModel->reveal(),
            $sebaksController->reveal(),
            $this->error->reveal()
        );

        $sebaksController->dispatch($this->request->reveal(), $this->response->reveal())->willReturn(null);
    }

    public function testOnDispatch()
    {
        $data = ['key' => 'value'];
        $this->response->getCriteriaErrors()->willReturn([]);
        $this->response->getChangesErrors()->willReturn([]);
        $this->response->toArray()->willReturn($data);
        $this->viewModel->setVariables($data)->willReturn(null);
        $this->event->setResult($this->viewModel->reveal());

        $result = $this->controller->onDispatch($this->event->reveal());

        $this->assertEquals($this->viewModel->reveal(), $result);
    }

    public function testOnDispatchWithCriteriaError()
    {
        $data = ['key' => 'value'];
        $errorData = ['errorKey' => 'errorValue'];
        $this->response->getCriteriaErrors()->willReturn($errorData);
        $this->error->notFoundByRequestedCriteria(
            $this->event->reveal(),
            $this->viewModel->reveal()
        )->willReturn($this->viewModel->reveal());

        $this->response->getChangesErrors()->willReturn([]);
        $this->response->toArray()->willReturn($data);
        $this->viewModel->setVariables($data)->willReturn(null);
        $this->event->setResult($this->viewModel->reveal());

        $result = $this->controller->onDispatch($this->event->reveal());

        $this->assertEquals($this->viewModel->reveal(), $result);
    }

    public function testOnDispatchWithChangesError()
    {
        $data = ['key' => 'value'];
        $errorData = ['errorKey' => 'errorValue'];
        $this->response->getCriteriaErrors()->willReturn([]);
        $this->response->getChangesErrors()->willReturn($errorData);

        $this->response->toArray()->willReturn($data);
        $this->viewModel->setVariables($data)->willReturn(null);
        $this->event->setResult($this->viewModel->reveal());

        $result = $this->controller->onDispatch($this->event->reveal());

        $this->assertEquals($this->viewModel->reveal(), $result);
    }
}
