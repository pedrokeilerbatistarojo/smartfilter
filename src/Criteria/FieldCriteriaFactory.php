<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Criteria;

use Pedrokeilerbatistarojo\Smartfilter\Contracts\CriteriaFactoryInterface;
use Pedrokeilerbatistarojo\Smartfilter\Enums\OperationTypeEnum;
use InvalidArgumentException;

class FieldCriteriaFactory implements CriteriaFactoryInterface
{
    public function create($key, $operator, $value, $operationType = OperationTypeEnum::AND->value, $relation = null)
    {
        if (strtolower($operator) == 'like') {
            $value = "%$value%";
        }

        if(!empty($relation)){
            return new RelationCriteria($key, $operator, $value, $operationType, $relation);
        }

        return new FieldCriteria($key, $operator, $value, $operationType);
    }
}
