<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Criteria;

use Pedrokeilerbatistarojo\Smartfilter\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class FieldCriteriaOr implements CriteriaInterface
{
    protected string $field;

    protected string $operator;

    protected mixed $value;

    public function __construct(string $field, string $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * Apply criteria Builder
     */
    public function apply(Builder $builder): Builder
    {

        //Todo: move to better place
        if (strtolower($this->operator) === 'in') {
            return $builder->orWhereIn($this->field, $this->value);
        }

        return $builder->orWhere($this->field, $this->operator, $this->value);
    }
}
