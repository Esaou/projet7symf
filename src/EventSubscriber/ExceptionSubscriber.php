<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $message = '';

        if ($exception instanceof BadRequestHttpException) {
            $message = 'Données de la requête invalides.';
        }

        if ($exception instanceof \InvalidArgumentException) {
            $message = 'Paramètres invalides.';
        }

        if ($exception instanceof NotFoundHttpException) {
            $message = 'Page introuvable';
        }

        dd($exception);
        $response = new JsonResponse([$message]);

        $event->setResponse($response);
    }
}