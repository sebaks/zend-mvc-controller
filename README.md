[![Build Status](https://travis-ci.org/sebaks/zend-mvc-controller.svg?branch=master)](https://travis-ci.org/sebaks/zend-mvc-controller)
[![codecov.io](http://codecov.io/github/sebaks/zend-mvc-controller/coverage.svg?branch=master)](http://codecov.io/github/sebaks/zend-mvc-controller?branch=master)

# Zend MVC controller implementation
This is controller implementation for Zend MVC builds on [sebaks/controller](https://github.com/sebaks/controller).

Most controllers must do
1. Check if that controller\action can be accessed by called method (GET, POST, PUT etc.), if not - rise exception.
2. Process request (this is responsibility of [sebaks/controller](https://github.com/sebaks/controller)).
  1. Validate request criteria.
  2. Validate request data.
  3. Process request (run any domain service).
  4. Collect errors.
  5. Collect result.
3. Rise exceptions if error exists.
4. Redirect to next page (if define).
5. Setup ViewModel.
6. Setup MVC Event.

That solution allow to customize any flow parameter and increases code reuse.

Installation
============

1. Install it via composer by running:

   ```sh
   composer require sebaks/zend-mvc-controller
   ```
2. Copy `./vendor/sebaks/zend-mvc-controller/config/sebaks-zend-mvc-controller.global.php.dist` to
   `./config/autoload/sebaks-zend-mvc-controller.global.php`.

Configuration
============
You can configure that controller with route params:
```php
'router' => [
    'routes' => [
        'user-update-profile' => [
            'type' => 'Segment',
            'options' => [
                'route'    => '/profile/update',
                'defaults' => [
                    'controller' => 'sebaks-zend-mvc-controller',
                    'allowedMethods' => ['POST'],
                    'criteriaValidator' => Users\Action\Profile\CriteriaValidator::class,
                    'changesValidator' => Users\Action\Profile\ChangesValidator::class,
                    'service' => Users\Action\Profile\Updater::class,
                    'request' => Sebaks\Controller\RequestInterface::class,
                    'routeCriteria' => 'id'
                    'response' => Sebaks\Controller\ResponseInterface::class,
                    'redirectTo' => 'admin-user-list',
                    'viewModel' => Users\User\ViewModel::class,
                ],
            ],
        ],
    ],
],
```

`criteriaValidator`, `changesValidator` - if not defined, will be created `Sebaks\Controller\EmptyValidator`
`service` - if not defined, will be created `Sebaks\Controller\EmptyService`  
`request` - if not defined, will be created `Sebaks\Controller\Request`  
`response` - if not defined, will be created `Sebaks\Controller\Response`  
`viewModel` - if not defined, will be created `Zend\View\Model\ViewModel`  
