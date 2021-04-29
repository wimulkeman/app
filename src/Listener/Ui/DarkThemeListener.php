<?php

namespace App\Listener\Ui;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DarkThemeListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!$request->query->has('_dark')) {
            return;
        }

        $session = $request->getSession();
        $value = (bool) $request->query->get('_dark');
        $session->set('dark_theme', $value);
    }
}
