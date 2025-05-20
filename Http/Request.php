<?php

namespace Brisa\Http;

class Request
{
    /**
     * Retorna o método http da requisição.
     *
     * @return string
     */
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}
