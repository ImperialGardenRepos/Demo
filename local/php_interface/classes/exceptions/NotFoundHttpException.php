<?php

namespace ig\Exceptions;

use Exception;
use Throwable;

class NotFoundHttpException extends Exception
{
    public function __construct(Throwable $previous = null)
    {
        $message = '404 HTTP Not found';
        $code = 404;
        parent::__construct($message, $code, $previous);
    }
}