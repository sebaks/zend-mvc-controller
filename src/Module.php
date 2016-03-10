<?php

namespace Sebaks\ZendMvcController;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig($env = null)
    {
        return include dirname(__DIR__) . '/config/module.config.php';
    }
}
