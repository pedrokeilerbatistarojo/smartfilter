<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Tests\Unit;

use Pedrokeilerbatistarojo\Smartfilter\Helpers\ResponseHelper;
use Pedrokeilerbatistarojo\Smartfilter\Services\FilterService;
use Pedrokeilerbatistarojo\Smartfilter\Tests\TestCase;
use Workbench\App\Models\User;
use Workbench\Database\Factories\RoleFactory;
use Workbench\Database\Factories\UserFactory;

class FilterTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function test_example_filter()
    {
        $role = RoleFactory::new()->create([
            'name' => 'Owner'
        ]);

        $users = UserFactory::new()->count(10)->create([
            'role_id' => $role->id
        ]);

        $filters = [
            ['name', 'like', 'Owner', 'and'],
            ['email', 'like', 'owner@example.com', 'and'],
            ['name', 'like', 'Owner', 'and', 'role']
        ];

        $columns = ['id', 'name', 'email'];
        $includes = ['role'];

        $params = [
            'filters' => $filters,
            'columns' => $columns,
            'includes' => $includes,
            'sortField' => 'created_at',
            'sortType' => 'asc',
            'itemsPerPage' => 8,
            'currentPage' => 1
        ];

        $filterService = new FilterService();
        $result = $filterService->execute(User::class, $params);
        $jsonResponse = ResponseHelper::sendResponse(
            $result,
            'Search completed successfully'
        )->getData(true);

        $this->assertArrayHasKey('success', $jsonResponse);
        $this->assertTrue($jsonResponse['success']);
        $this->assertEquals('Search completed successfully', $jsonResponse['message']);
        $this->assertEmpty($jsonResponse['errors']);

        $payload = $jsonResponse['payload'];
        $this->assertCount(8, $payload['items']);
        $this->assertEquals(10, $payload['total']);

        $user = $payload['items'][0];
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('role', $user);
        $this->assertEquals('Owner', $user['role']['name']);

        $metadata = $payload['metadata'];
        $this->assertEquals(1, $metadata['currentPage']);
        $this->assertEquals(2, $metadata['lastPage']);
        $this->assertEquals(8, $metadata['itemsPerPage']);
        $this->assertEquals(10, $metadata['total']);
    }
}