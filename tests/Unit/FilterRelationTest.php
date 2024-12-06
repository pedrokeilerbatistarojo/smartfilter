<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Tests\Unit;

use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;
use Pedrokeilerbatistarojo\Smartfilter\Helpers\ResponseHelper;
use Pedrokeilerbatistarojo\Smartfilter\Services\FilterService;
use Pedrokeilerbatistarojo\Smartfilter\Tests\TestCase;
use Workbench\App\Models\Role;
use Workbench\App\Models\User;

class FilterRelationTest extends TestCase
{

    /**
     * @throws Exception
     */
    #[NoReturn]
    public function test_relation_with_filter()
    {
        $role = Role::create([
            'name' => 'Owner'
        ]);

        for ($i = 0; $i < 10; $i++) {
            $nameFilterable = $this->faker->name();
            $emailFilterable = $this->faker->safeEmail();

            User::create([
                'name' => $nameFilterable,
                'email' => $emailFilterable,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'role_id' => $role->id
            ]);
        }

        $filters = [
            ['name', 'like', $nameFilterable, 'and'],
            ['email', 'like', $emailFilterable, 'and'],
            ['name', 'like', 'Owner', 'and', 'role']
        ];

        $columns = ['id', 'name', 'email', 'role_id'];
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
        $this->assertCount(1, $payload['items']);
        $this->assertEquals(1, $payload['total']);

        $user = $payload['items'][0];
        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('name', $user);
        $this->assertArrayHasKey('email', $user);
        $this->assertArrayHasKey('role', $user);
        $this->assertEquals('Owner', $user['role']['name']);

        $metadata = $payload['metadata'];
        $this->assertEquals(1, $metadata['currentPage']);
        $this->assertEquals(1, $metadata['lastPage']);
        $this->assertEquals(8, $metadata['itemsPerPage']);
        $this->assertEquals(1, $metadata['total']);
    }
}