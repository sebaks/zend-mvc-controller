<?php

namespace Sebaks\ZendMvcController;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractController;
use Zend\View\Model\ModelInterface as ViewModelInterface;
use Zend\Http\Response;
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
        array $options = []
    ) {

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
            &&  !in_array($this->sebaksRequest->getMethod(), $this->options['allowedMethods'])) {
            return $this->error->methodNotAllowed();
        }

        $this->controller->dispatch($this->sebaksRequest, $this->sebaksResponse);

        $criteriaErrors = $this->sebaksResponse->getCriteriaErrors();
        if (!empty($criteriaErrors)) {
            return $this->error->notFoundByRequestedCriteria($criteriaErrors);
        }

        $changesErrors = $this->sebaksResponse->getChangesErrors();
        $redirectTo = $this->sebaksResponse->getRedirectTo();
        if (empty($changesErrors) && !empty($redirectTo)) {
            if (is_array($redirectTo)) {
                if (!isset($redirectTo['route'])) {
                    throw new \RuntimeException('Missing required parameter route');
                }
                $routeParams = isset($redirectTo['params']) ? $redirectTo['params'] : [];
                $routeOptions = isset($redirectTo['options']) ? $redirectTo['options'] : [];

                return $this->redirect()->toRoute($redirectTo['route'], $routeParams, $routeOptions);
            } else {
                return $this->redirect()->toRoute($redirectTo);
            }
        }

        if (!empty($changesErrors)) {
            $result =  $this->error->changesErrors($changesErrors);
            if ($result instanceof Response) {
                return $result;
            }
        }

        $this->viewModel->setVariables($this->sebaksResponse->toArray());

        $e->setResult($this->viewModel);

        return $this->viewModel;
    }
}
