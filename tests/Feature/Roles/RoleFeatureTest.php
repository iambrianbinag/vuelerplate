<?php

namespace Tests\Feature\Roles;

use App\Models\Permissions\Permission;
use App\Models\Roles\Role;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RoleFeatureTest extends TestCase
{
    /** @test */
    public function it_can_return_all_roles()
    {
        Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/roles')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('data')
                    ->has('data.0', function($json){
                        $json
                            ->has('id')
                            ->has('name');
                    })
                    ->etc();
            });
    }

    /** @test */
    public function it_can_return_all_roles_not_paginated()
    {
        Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/roles?not_paginated=true')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->first(function($json){
                        $json
                            ->has('id')
                            ->has('name');
                    });
            });
    }

    /** @test */
    public function it_can_search_roles()
    {
        $role = Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/roles/?search=$role->name")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role){
                $json
                    ->has('data')
                    ->has('data.0', function(AssertableJson $json){
                        $json
                            ->has('id')
                            ->has('name');
                    })
                    ->etc();
            });
    }

    /** @test */
    public function it_can_find_a_role_by_id()
    {
        $role = Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/roles/$role->id")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role){
                $json
                    ->where('id', $role->id)
                    ->where('name', $role->name);
            });
    }

    /** @test */
    public function it_can_create_role()
    {
        $data = ['name' => $this->faker->name];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/roles', $data)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($data){
                $json
                    ->has('id')
                    ->where('name', $data['name']);
            });

        $this->assertDatabaseHas('roles', $data);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $role = Role::factory()->create();

        $update = ['name' => $this->faker->name];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/roles/$role->id", $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role, $update){
                $json
                    ->where('id', $role->id)
                    ->where('name', $update['name']);
            });

        $this->assertDatabaseHas('roles', $update);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        $role = Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->deleteJson("/api/roles/$role->id")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role){
                $json
                    ->where('id', $role->id)
                    ->where('name', $role->name);
            });

        $this->assertDatabaseMissing('roles', $role->toArray());
    }

    /** @test */
    public function it_can_give_permissions_to_role()
    {
        $permissions = Permission::factory(5)->create();
        $permissionIds = $permissions->pluck('id')->toArray();
        $role = Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->postJson("/api/roles/$role->id/permissions", ['permission_ids' => $permissionIds])
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role){
                $json
                    ->where('id', $role->id)
                    ->where('name', $role->name)
                    ->has('permissions', 5, function($json){
                        $json
                            ->has('id')
                            ->has('name');
                    });
            });
    }

    /** @test */
    public function it_can_sync_permissions_to_role()
    {
        $permissions = Permission::factory(5)->create();
        $permissionIds = $permissions->pluck('id')->toArray();
        $role = Role::factory()->create();

        $response = $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/roles/$role->id/permissions", ['permission_ids' => $permissionIds])
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role){
                $json
                    ->where('id', $role->id)
                    ->where('name', $role->name)
                    ->has('permissions', 5, function($json){
                        $json
                            ->has('id')
                            ->has('name');
                    });
            });

        $permissionIdsFromResponse = collect($response['permissions'])->pluck('id')->toArray();
        $this->assertEqualsCanonicalizing($permissionIds, $permissionIdsFromResponse);
    }

    /** @test */
    public function it_can_list_permissions_of_role()
    {
        $permissions = Permission::factory(5)->create();
        $permissionIds = $permissions->pluck('id')->toArray();
        $role = Role::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->postJson("/api/roles/$role->id/permissions", ['permission_ids' => $permissionIds]);

        $response = $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/roles/$role->id/permissions")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($role){
                $json
                    ->where('id', $role->id)
                    ->where('name', $role->name)
                    ->has('permissions', 5, function($json){
                        $json
                            ->has('id')
                            ->has('name')
                            ->has('order');
                    });
            });

        $permissionIdsFromResponse = collect($response['permissions'])->pluck('id')->toArray();
        $this->assertEqualsCanonicalizing($permissionIds, $permissionIdsFromResponse);
    }

    /** @test */
    public function it_can_log_created_role()
    {
        $responseData = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/roles', ['name' => $this->faker->name]);

        $logData = [
            'log_name' => 'role',
            'description' => 'created',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
    }

    /** @test */
    public function it_can_log_updated_role()
    {
        $role = Role::factory()->create();

        $responseData = $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/roles/$role->id", ['name' => $this->faker->name]);

        $logData = [
            'log_name' => 'role',
            'description' => 'updated',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
    }

    /** @test */
    public function it_can_log_synced_permissions_to_role()
    {
        $permissionIds = Permission::factory(5)->create()->pluck('id')->toArray();
        $role = Role::factory()->create();

        $responseData = $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/roles/$role->id/permissions", ['permission_ids' => $permissionIds]);

        $logData = [
            'log_name' => 'role',
            'description' => 'updated',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
    }
}
