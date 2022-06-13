<?php


namespace App\Service;


use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BodyValidator
{
    public function bodyValidate(string $body)
    {
        $body = json_decode($body, true);

        if (empty($body) || array_key_exists('roles', $body)) {
            throw new BadRequestHttpException();
        }
    }
}