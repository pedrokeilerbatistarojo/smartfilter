<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Traits;

trait TraitHandleResponsePayload
{
    protected array $payload = [];

    /**
     * @throws \Exception
     */
    public function setPayload(mixed $payload): void
    {

        if (! is_array($payload)) {
            throw new \Exception('Payload must be a instance of array');
        }

        $this->payload = $payload;
    }
}
