<?php
// src/Cupon/UsuarioBundle/Listener/LoginListener.php
namespace CF\HistoryLogBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
    {
        $usuario = $event->getAuthenticationToken()->getUser();
    }
}