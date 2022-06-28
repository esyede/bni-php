<?php

namespace Esyede\BNI\Exceptions;

class InvalidBNIException extends \Exception
{
    public function toArray()
    {
        return [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTrace(),
            'trace_string' => $this->getTraceAsString(),
        ];
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}