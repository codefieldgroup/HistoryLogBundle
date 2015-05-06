<?php

namespace Cf\HistoryLogBundle\Listener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RequestListener
{
    public function onKernelRequest( GetResponseEvent $event )
    {
        //$event->getRequest()->setFormat('pdf', 'application/pdf');

        $file_data = fopen( '/tmp/loguetes_1.log', "w+" );
        fwrite( $file_data, 'onKernelRequest' );

    }

    public function onSecurityInteractiveLogin( InteractiveLoginEvent $event )
    {
        $file_data = fopen( '/tmp/loguetes_2.log', "w+" );
        fwrite( $file_data, 'onSecurityInteractiveLogin' );
    }
}