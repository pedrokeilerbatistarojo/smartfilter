<?php

declare(strict_types=1);

namespace Pedrokeilerbatistarojo\Smartfilter\Dtos;

use Pedrokeilerbatistarojo\Smartfilter\Enums\OperationTypeEnum;

class FilterItemObject
{
    public function __construct(
        public string  $field,
        public string  $operation,
        public mixed   $value,
        public string  $operationType = OperationTypeEnum::AND->value,
        public ?string $relation = null,
    ){
    }
}
