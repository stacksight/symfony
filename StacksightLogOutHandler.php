<?php
namespace AppBundle\Stacksight;

use AppBundle\Stacksight\Stacksight;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class StacksightLogOutHandler implements LogoutSuccessHandlerInterface
{
    private $token;
    private $stacksight;

    const EVENT_LOGOUT = 'logged out';

    public function __construct(TokenStorage $token, Stacksight $stacksight)
    {
        $this->token = $token;
        $this->stacksight = $stacksight->getClient();
    }

    public function onLogoutSuccess(Request $request)
    {
        $user = $this->token->getToken()->getUser();
        $referer = $request->headers->get('referer');
        $username = $user->getUsername();
        $email = $user->getEmail();

        $event = array();
        $event['user'] = array('name' => ($username) ? $username : $email);
        $this->stacksight->publishEvent(array(
                'action' => self::EVENT_LOGOUT,
                'type' => 'user',
                'name' => '',
            ) + $event);

        return new RedirectResponse($referer);
    }
}