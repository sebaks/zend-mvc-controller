<?php

namespace Sebaks\ZendMvcController;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractController;
use Zend\View\Model\ModelInterface as ViewModelInterface;
use Sebaks\Controller\Controller as SebaksController;
use Sebaks\Controller\RequestInterface;
use Sebaks\Controller\ResponseInterface;

class Controller extends AbstractController
{
    /**
     * @var RequestInterface
     */
    private $sebaksRequest;

    /**
     * @var ResponseInterface
     */
    private $sebaksResponse;

    /**
     * @var ViewModelInterface
     */
    private $viewModel;

    /**
     * @var SebaksController
     */
    private $controller;

    /**
     * @var ErrorInterface
     */
    private $error;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @param RequestInterface $sebaksRequest
     * @param ResponseInterface $sebaksResponse
     * @param ViewModelInterface $viewModel
     * @param SebaksController $controller
     * @param ErrorInterface $error
     * @param array $options
     */
    public function __construct(
        RequestInterface $sebaksRequest,
        ResponseInterface $sebaksResponse,
        ViewModelInterface $viewModel,
        SebaksController $controller,
        ErrorInterface $error,
        array $options = [])
    {
        $this->sebaksRequest = $sebaksRequest;
        $this->sebaksResponse = $sebaksResponse;
        $this->viewModel = $viewModel;
        $this->controller = $controller;
        $this->error = $error;
        $this->options = $options;
    }

    /**
     * @param MvcEvent $e
     * @return mixed|\Zend\Http\Response|ViewModelInterface
     */
    public function onDispatch(MvcEvent $e)
    {
        if (!empty($this->options['allowedMethods'])
            &&  !in_array($this->sebaksRequest->getMethod(), $this->options['allowedMethods']))
        {
            return $this->error->methodNotAllowed($e, $this->viewModel);
        }

        $this->controller->dispatch($this->sebaksRequest, $this->sebaksResponse);

        $criteriaErrors = $this->sebaksResponse->getCriteriaErrors();
        if (!empty($criteriaErrors)) {
            return $this->error->notFoundByRequestedCriteria($e, $this->viewModel);
        }

        $changesErrors = $this->sebaksResponse->getChangesErrors();
        if (empty($changesErrors) && !empty($this->options['redirectTo'])) {
            return $this->redirect()->toRoute($this->options['redirectTo']);
        }

        $this->viewModel->setVariables($this->sebaksResponse->toArray());

        $e->setResult($this->viewModel);

        return $this->viewModel;
    }
}