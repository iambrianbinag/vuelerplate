<?php

namespace Tests;

use App\Models\Users\User;
use App\Models\Roles\Role;
use App\Models\Permissions\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Services\Cache\Interfaces\CacheInterface;
use Faker\Factory as Faker;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $faker;
    protected $user;

    public function setUp() : void 
    {
        parent::setUp();

        // $this->withoutExceptionHandling();

        $this->faker = Faker::create();
        $this->user = User::factory()->create();

        // Create permissions
        $permissions = collect(config('permission_seeder.permissions'))->map(function($permission){
            return Permission::create(['name' => $permission]);
        });

        // Create role and assign all permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo($permissions);

        // Assign role to user
        $this->user->assignRole($adminRole);
    }

    public function tearDown() : void
    {
        $cacheService =  app(CacheInterface::class);
        $cacheService->command('FLUSHALL');

        parent::tearDown();
    }
}
