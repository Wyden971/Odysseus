<?php 

namespace Odysseus\AdminBundle\Listener;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class RequestListener
{
    public function onKernelRequest(GetResponseEvent  $event)
    {
        if (HttpKernel::MASTER_REQUEST != $event->getRequestType()) {
            return;
        }
    }
}