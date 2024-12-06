<?php

namespace Pedrokeilerbatistarojo\Smartfilter\Tests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use function Orchestra\Testbench\workbench_path;
use Faker\Factory as FakerFactory;
use Faker\Generator;


abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected Generator $faker;

    protected function initializeFaker(): void
    {
        $this->faker = FakerFactory::create();
    }

    protected function setUp(): void
    {
        $this->initializeFaker();
        parent::setUp();
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testing');
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }
}
