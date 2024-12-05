<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Services;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Pedrokeilerbatistarojo\Smartfilter\Dtos\SearchRequest;
use Pedrokeilerbatistarojo\Smartfilter\Traits\TraitHandleListPayload;
use Pedrokeilerbatistarojo\Smartfilter\Traits\TraitSearchResult;

class FilterService
{
    use TraitSearchResult;
    use TraitHandleListPayload;

    /**
     * @throws Exception
     */
    public function execute(string $modelPath, array $inputs = []): LengthAwarePaginator
    {
        if (class_exists($modelPath)) {
            $entity = new $modelPath();
        } else {
            throw new Exception("The class {$modelPath} does not exist.");
        }

        if (!isset($entity)) {
            throw new Exception('The model is not configured in the service.');
        }

        $this->setPayload(new SearchRequest($inputs));
        return $this->searchServiceResult($entity, $this->payload);
    }
}
