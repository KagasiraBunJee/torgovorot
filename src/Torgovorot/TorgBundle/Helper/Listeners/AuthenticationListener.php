<?php

namespace Torgovorot\TorgBundle\Helper\Listeners;

use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Description of AuthenticationListener
 *
 * @author serg
 */
class AuthenticationListener {
    /**
     * onAuthenticationFailure
     *
     * @author     serg
     * @param     AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailure( AuthenticationFailureEvent $event )
    {
        echo "bad";
    }
 
    /**
     * onAuthenticationSuccess
     *
     * @author     serg
     * @param     InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess( InteractiveLoginEvent $event )
    {
        // executes on successful login
    }
}
