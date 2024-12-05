<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Contracts;

interface CriteriaFactoryInterface
{
    public function create($key, $operator, $value, $operationType);
}
