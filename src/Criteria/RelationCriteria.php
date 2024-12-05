<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Criteria;

use Pedrokeilerbatistarojo\Smartfilter\Contracts\CriteriaInterface;
use Illuminate\Database\Eloquent\Builder;

class RelationCriteria implements CriteriaInterface
{
    public function __construct(
        public string $field,
        public string $operator,
        public $value,
        public $operationType,
        public string $relation,
    ) {}

    /**
     * Apply criteria Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->whereHas($this->relation, function (Builder $query) {
            if ($this->operationType === 'or'){
                $query->orWhere($this->field, $this->operator, $this->value);
            }else{
                $query->where($this->field, $this->operator, $this->value);
            }
        });
    }
}
