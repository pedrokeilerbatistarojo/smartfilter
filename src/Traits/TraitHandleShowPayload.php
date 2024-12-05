<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Traits;

use Pedrokeilerbatistarojo\Smartfilter\Dtos\ShowInputObject;

trait TraitHandleShowPayload
{
    private ?ShowInputObject $payload = null;

    /**
     * @throws \Exception
     */
    public function setPayload(mixed $payload): void
    {
        if (! $payload instanceof ShowInputObject) {
            throw new \Exception('payload must be a instance of ShowInputObject');
        }

        $this->payload = $payload;
    }
}
