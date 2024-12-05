<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface CriteriaInterface
{
    /**
     * Apply criteria Builder
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder;
}
