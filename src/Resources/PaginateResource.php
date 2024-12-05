<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->items,
            'totals' => $this->totals,
            'metadata' => $this->paginationData,
        ];
    }
}
