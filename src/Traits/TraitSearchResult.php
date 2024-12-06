<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Traits;

use Pedrokeilerbatistarojo\Smartfilter\Criteria\FieldCriteriaFactory;
use Pedrokeilerbatistarojo\Smartfilter\Dtos\SearchRequest;
use Pedrokeilerbatistarojo\Smartfilter\Enums\SQLSortEnum;
use Pedrokeilerbatistarojo\Smartfilter\Services\SearchRepositoryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;

trait TraitSearchResult
{
    /**
     * @throws \Exception
     */
    protected function searchServiceResult(Model $entity, SearchRequest $request): LengthAwarePaginator
    {
        $columns = ! empty($request->columns) ? $request->columns : ['*'];
        $includes = $request->includes;
        $sortType = $request->sortType ? SQLSortEnum::from($request->sortType) : SQLSortEnum::SORT_DESC;
        $sortField = $request->sortField;
        $itemsPerPage = $request->itemsPerPage;
        $currentPage =  $request->currentPage;

        if (! $sortField) {
            $sortField = $entity->getTable().'.id';
        }

        $criteria = [];
        $factoryCriteria = new FieldCriteriaFactory();

        foreach ($request->filters as $filter) {
            $relation = $filter->relation;
            if (!empty($relation)){
                if (!(method_exists($entity, $relation) && $entity->$relation() instanceof Relation)) {
                    throw new \Exception("The relation '$relation' is not valid.");
                }
            }

            $criteria[] = $factoryCriteria->create(
                $filter->field,
                $filter->operator,
                $filter->value,
                $filter->operationType,
                $filter->relation,
            );
        }

        return SearchRepositoryService::search(
            $entity,
            $criteria,
            $columns,
            $includes,
            $sortField,
            $sortType,
            $itemsPerPage,
            $currentPage
        );
    }
}
