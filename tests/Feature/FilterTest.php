<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Tests\Feature;

use Tests\TestCase;

class FilterTest extends TestCase
{

    /**
     * An advance feature test example.
     */
    public function test_filter_with_filters(): void
    {
        $filters = [
            ['name','like','Owner', 'and'],
            ['email','like','owner@example.com', 'and'],
            ['name','like','Owner', 'and','role'],
        ];

        $columns = ['id', 'name', 'email'];

        $includes = ['role'];

        $params  = [
            'filters' => $filters,
            'columns' => $columns,
            'includes' => $includes,
            'sortField' => 'created_at',
            'sortType' => 'asc',
            'itemsPerPage' => 8,
            'currentPage' => 1
        ];

        $queryString = http_build_query($params);
        $endpoint = "/api/users?{$queryString}";

        $response = $this->get($endpoint);
        $response->assertStatus(200);
        $response->assertJsonStructure($this->arrStructureList());
    }

    private function arrStructureList(): array
    {
        return [
            'success',
            'message',
            'errors',
            'payload' => [
                'items' => [
                    '*' => [
                        'id',
                        'name',
                        'email'
                    ],
                ],
                'metadata' => [
                    'currentPage',
                    'lastPage',
                    'itemsPerPage',
                    'total',
                ],
                'total'
            ],
        ];
    }
}
