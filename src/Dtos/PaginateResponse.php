<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Dtos;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginateResponse
{
    public ResourceCollection|array $items;

    public array $paginationData;

    public array $totals;
}
