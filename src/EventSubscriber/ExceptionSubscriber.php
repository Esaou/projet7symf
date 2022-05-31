<?php

namespace App\EventSubscriber;

use App\CustomException\FormErrorException;
use App\CustomException\ItemNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

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
        $status = 200;

        if ($exception instanceof BadRequestHttpException) {
            $message = 'Données de la requête invalides.';
            $status = 400;
        }

        if ($exception instanceof \InvalidArgumentException) {
            $message = 'Paramètre invalide.';
            $status = 400;
        }

        if ($exception instanceof NotFoundHttpException) {
            $message = 'Le endpoint est introuvable.';
            $status = 404;
        }

        if ($exception instanceof FormErrorException) {
            $message = $exception->getMessage();
            $status = 400;
        }

        if ($exception instanceof ItemNotFoundException) {
            $message = $exception->getMessage();
            $status = 404;
        }

        if ($exception instanceof NotEncodableValueException) {
            $message = "Erreur de syntaxe.";
            $status = 400;
        }

        $response = new JsonResponse([
            'message' => $message
        ], $status);

        $event->setResponse($response);
    }
}