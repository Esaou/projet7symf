<?php


namespace App\Service;


use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class BodyValidator
{
    private DecoderInterface $decoder;

    public function __construct(DecoderInterface $decoder)
    {
        $this->decoder = $decoder;
    }

    #[NoReturn] public function bodyValidate(string $body)
    {
        $body = $this->decoder->decode($body, 'json');

        if (empty($body) || array_key_exists('roles', $body)) {
            throw new BadRequestHttpException();
        }
    }
}