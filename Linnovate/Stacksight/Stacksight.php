<?php

namespace Linnovate\Stacksight;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class Stacksight
{
    private $_ss_client;
    private $container;

    public function __construct(Container $container){
        $this->container = $container;
        if($container->hasParameter('stacksight.token')){
            $token = $container->getParameter('stacksight.token');

            if(!defined('STACKSIGHT_TOKEN') && $token){
                define('STACKSIGHT_TOKEN', $token);
            }
        } else{
            return;
            // We can notice that need set stacksight token
        }

        if($container->hasParameter('stacksight.app_id')){
            $app_id = $container->getParameter('stacksight.app_id');
            if(!defined('STACKSIGHT_APP_ID') && $app_id){
                define('STACKSIGHT_APP_ID', $app_id);
            }
        }

        if($container->hasParameter('stacksight.group')){
            $group = $container->getParameter('stacksight.group');
            if(!defined('STACKSIGHT_GROUP') && $group){
                define('STACKSIGHT_GROUP', $group);
            }
        }

        if($container->hasParameter('stacksight.logs')){
            $include_logs = $container->getParameter('stacksight.logs');
            if(!defined('STACKSIGHT_INCLUDE_LOGS') && $include_logs){
                define('STACKSIGHT_INCLUDE_LOGS', $include_logs);
            }
        }

        if($container->hasParameter('stacksight.debug')){
            $debug = $container->getParameter('stacksight.debug');
            if(!defined('STACKSIGHT_DEBUG') && $debug){
                define('STACKSIGHT_DEBUG', (bool) $debug);
            }
        }

        global $ss_client;
        if(!$ss_client){
            require_once('src/sdk/bootstrap-symfony-2.php');
            $stacksight = new \SymfonyBootstrap();
            $this->_ss_client = $stacksight->getClient();
        } else{
            $this->_ss_client = $ss_client;
        }
    }

    public function getClient(){
        return $this->_ss_client;
    }
}