<?php


namespace App\CustomException;


use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class FormErrorException extends HttpException
{
    public function __construct(ConstraintViolationListInterface $constraintViolationList = null, \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        $errors = $constraintViolationList->getIterator()->getArrayCopy();

        $message = '';

        foreach ($errors as $error) {
            $message .= $error->getMessage() . ' ';
        }

        parent::__construct(400, $message, $previous, $headers, $code);
    }
}