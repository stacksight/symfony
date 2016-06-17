<?php

namespace AppBundle\Stacksight;

use AppBundle\Stacksight\Stacksight;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class StacksightSecurityEvents
{
    private $stacksight;

    const EVENT_LOGIN = 'logged in';

    public function __construct(Stacksight $stacksight)
    {
        $this->stacksight = $stacksight->getClient();
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $username = $user->getUsername();
        $email = $user->getEmail();

        $event = array();
        $event['user'] = array('name' => ($username) ? $username : $email);
        $this->stacksight->publishEvent(array(
                'action' => self::EVENT_LOGIN,
                'type' => 'user',
                'name' => '',
            ) + $event);
    }
}