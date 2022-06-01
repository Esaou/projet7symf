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
use Symfony\Contracts\Translation\TranslatorInterface;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
            $message = $this->translator->trans('invalid.request', [], 'validator');
            $status = 400;
        }

        if ($exception instanceof \InvalidArgumentException) {
            $message = $this->translator->trans('invalid.parameter', [], 'validator');
            $status = 400;
        }

        if ($exception instanceof NotFoundHttpException) {
            $message = $this->translator->trans('not.found', [], 'validator');
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
            $message = $this->translator->trans('invalid.syntax', [], 'validator');
            $status = 400;
        }

        $response = new JsonResponse([
            'message' => $message
        ], $status);

        $event->setResponse($response);
    }
}