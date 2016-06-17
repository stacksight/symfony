<?php

namespace AppBundle\Stacksight;

//use Symfony\Component\HttpKernel\Event\GetResponseEvent;
//use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
//use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
//use Symfony\Component\HttpKernel\KernelEvents;
//use Symfony\Component\HttpFoundation\RequestMatcherInterface;
//use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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

        global $ss_client;
        if(!$ss_client){
            require_once('src/sdk/bootstrap-symfony-2.php');
            $stacksight = new \SymfonyBootstrap();
            $this->_ss_client = $stacksight->getClient();
        }
    }

    public function getClient(){
        return $this->_ss_client;
    }

//    /**
//     * Handles the onKernelException event.
//     *
//     * @param GetResponseForExceptionEvent $event A GetResponseForExceptionEvent instance
//     */
//    public function onKernelException(GetResponseForExceptionEvent $event)
//    {
//        if ($this->onlyMasterRequests && !$event->isMasterRequest()) {
//            return;
//        }
//
//        $this->exception = $event->getException();
//    }
//
//    /**
//     * @deprecated since version 2.4, to be removed in 3.0.
//     */
//    public function onKernelRequest(GetResponseEvent $event)
//    {
//        if (null === $this->requestStack) {
//            $this->requests[] = $event->getRequest();
//        }
//    }
}