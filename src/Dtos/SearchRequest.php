<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Dtos;

use Pedrokeilerbatistarojo\Smartfilter\Enums\OperationTypeEnum;
use Pedrokeilerbatistarojo\Smartfilter\Enums\PaginationParamsEnum;
use Exception;

class SearchRequest
{
    public mixed $filters = null;

    public mixed $columns = null;

    public mixed $includes = null;

    public ?string $sortField = null;

    public ?string $sortType = null;

    public ?int $itemsPerPage = null;

    public ?int $currentPage = null;

    /**
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->columns = array_key_exists('columns', $params) ?
            $this->processArrayParam($params['columns']) : [];

        $this->includes = array_key_exists('includes', $params) ?
            $this->processArrayParam($params['includes']) : [];

        $filters = array_key_exists('filters', $params) ?
            $this->processArrayParam($params['filters']) : [];

        if(!empty($filters)) {
            $filters = $this->processFilter($filters);
        }
        $this->filters = $filters;

        $this->sortField = array_key_exists('sortField', $params) ?
          $params['sortField'] : 'id';

        $this->sortType = array_key_exists('sortType', $params) ?
          $params['sortType'] : 'desc';

        $this->itemsPerPage = array_key_exists('itemsPerPage', $params) ?
          intval($params['itemsPerPage']) : PaginationParamsEnum::DEFAULT_ITEMS_PER_PAGE;

        $this->currentPage = array_key_exists('currentPage', $params) ?
          intval($params['currentPage']) : PaginationParamsEnum::DEFAULT_CURRENT_PAGE;

        $max =  PaginationParamsEnum::DEFAULT_MAX_ITEMS_PER_PAGE;

        if ($this->itemsPerPage > $max) {
            $this->itemsPerPage = $max;
        }
    }

    /**
     * Process param array
     *
     * @throws Exception
     */
    private function processArrayParam($param = null)
    {
        $result = [];

        if (empty($param)) {
            return $result;
        }

        if (is_string($param)) {
            $result = json_decode($param, true);

            if (is_string($result)) {
                $result = json_decode($result, true);
            }

            if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Error decoding JSON');
            }

        } else {
            $result = $param;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function processFilter(array $filters): array
    {
        $result = [];

        foreach ($filters as $filter) {

            $isInvalidFilter = !isset($filter[0]) || !isset($filter[2]);
            if($isInvalidFilter) throw new Exception('Invalid filters params');

            $field = $filter[0] ?? null;
            $operator = '=';
            $value = null;
            $operationType = OperationTypeEnum::AND->value;
            $relation = null;

            $len = count($filter);

            if($len < 2){

                throw new Exception("Invalid Parameters");

            }else if ( $len === 2){

                $value = $filter[1] ?? null;

            }else if($len === 3){

                if(is_array($filter[1])){
                    $value = $filter[1];
                    $operationType = $filter[2];
                    $this->validOperationType($operationType);
                }else{
                    $operator = $filter[1] ?? null;
                    $value = $filter[2] ?? null;
                }

            }else if($len === 4){

                $operator = $filter[1] ?? null;
                $value = $filter[2] ?? null;
                $operationType = $filter[3];
                $this->validOperationType($operationType);

            }else if($len === 5){

                $operator = $filter[1] ?? null;
                $value = $filter[2] ?? null;
                $operationType = $filter[3];
                $this->validOperationType($operationType);
                $relation = $filter[4] ?? null;
            }

            $filterObject = new FilterItemObject(
                $field,
                $operator,
                $value,
                $operationType,
                $relation
            );

            $result[] = $filterObject;
        }

        return $result;
    }

    /**
     * @throws Exception
     */
    private function validOperationType(string $operationType) : void
    {
        $operationType = strtolower($operationType);
        if(OperationTypeEnum::tryFrom($operationType) === null){
            throw new Exception("{$operationType} is an invalid operation type. Try: and, or, in");
        }
    }
}
