<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Services;

use Illuminate\Http\JsonResponse;
use Pedrokeilerbatistarojo\Smartfilter\Dtos\SearchRequest;
use Pedrokeilerbatistarojo\Smartfilter\Helpers\ResponseHelper;
use Pedrokeilerbatistarojo\Smartfilter\Traits\TraitHandleListPayload;
use Pedrokeilerbatistarojo\Smartfilter\Traits\TraitSearchResult;

class FilterService
{
    use TraitSearchResult;
    use TraitHandleListPayload;

    /**
     * @throws \Exception
     */
    public function execute(string $modelPath, array $inputs = []): JsonResponse
    {
        if (class_exists($modelPath)) {
            $entity = new $modelPath();
        } else {
            throw new \Exception("The class {$modelPath} does not exist.");
        }

        if (!isset($entity)) {
            throw new \Exception('The model is not configured in the service.');
        }

        try {
            $this->setPayload(new SearchRequest($inputs));
            $result = $this->searchServiceResult($entity, $this->payload);
            return ResponseHelper::sendResponse($result, "Search completed successfully");
        }catch (\Exception $ex){
            return ResponseHelper::sendError($ex->getMessage());
        }


    }
}
