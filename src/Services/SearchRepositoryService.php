<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Services;

use Pedrokeilerbatistarojo\Smartfilter\Contracts\CriteriaInterface;
use Pedrokeilerbatistarojo\Smartfilter\Enums\PaginationParamsEnum;
use Pedrokeilerbatistarojo\Smartfilter\Enums\SQLSortEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchRepositoryService
{
    public static function search(
        Model $entity,
        array $criteria = [],
        array $columns = ['*'],
        array $includes = [],
        string $sortField = 'id',
        SQLSortEnum $sortType = SQLSortEnum::SORT_DESC,
        int $itemsPerPage = PaginationParamsEnum::DEFAULT_ITEMS_PER_PAGE,
        int $currentPage = 1
    ): LengthAwarePaginator {

        $query = $entity->newQueryWithoutRelationships();
        $query->select($columns);

        foreach ($criteria as $criterion) {
            if ($criterion instanceof CriteriaInterface) {
                $criterion->apply($query);
            }
        }

        $query->with($includes);
        $query->orderBy($sortField, $sortType->value);

        return $query->paginate(perPage: $itemsPerPage, page: $currentPage);
    }
}
