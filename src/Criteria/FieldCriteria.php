<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Criteria;

use Pedrokeilerbatistarojo\Smartfilter\Contracts\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class FieldCriteria implements CriteriaInterface
{
    public function __construct(
        public string $field,
        public string $operator,
        public $value,
        public string $operationType,
    ) {}

    /**
     * Apply criteria Builder
     */
    public function apply(Builder $builder): Builder
    {
        if (strtolower($this->operationType) === 'in') {
            return $builder->whereIn($this->field, $this->value);
        }else if ($this->operationType === 'or'){
            return $builder->orWhere($this->field, $this->operator, $this->value);
        }

        return $builder->where($this->field, $this->operator, $this->value);
    }
}
