<?php

declare(strict_types=1);

namespace Pedrokeilerbatistarojo\Smartfilter\Dtos;

class ResponseObject
{
    public bool $success = true;

    public string $message = '';

    public array $errors = [];

    public mixed $payload = null;
}
