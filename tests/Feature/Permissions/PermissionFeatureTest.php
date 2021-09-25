<?php

namespace Tests\Feature\Permissions;

use App\Models\Permissions\Permission;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class PermissionFeatureTest extends TestCase
{
    /** @test */
    public function it_can_return_all_permissions()
    {
        Permission::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/permissions')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('data')
                    ->has('data.0', function($json){ 
                        $json
                            ->has('id')
                            ->has('name')
                            ->has('order');
                    })
                    ->etc();
            });
    }

    /** @test */
    public function it_can_return_all_permissions_not_paginated()
    {
        Permission::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/permissions/?not_paginated=1')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->first(function($json){
                        $json
                            ->has('id')
                            ->has('name')
                            ->has('order');
                    });
            });
    }

    /** @test */
    public function it_can_search_permissions()
    {
        $permission = Permission::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/permissions/?search=$permission->name")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('data')
                    ->has('data.0', function($json){
                        $json
                            ->has('id')
                            ->has('name')
                            ->has('order');
                    })
                    ->etc();
            });
    }

    /** @test */
    public function it_can_find_a_permission_by_id()
    {
        $permission = Permission::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/permissions/$permission->id")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($permission){
                $json
                    ->where('id', $permission->id)
                    ->where('name', $permission->name)
                    ->where('order', $permission->order);
            });
    }

    /** @test */
    public function it_cannot_find_a_permission_by_id()
    {
        $invalidPermissionId = 'invalid';

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/permissions/$invalidPermissionId")
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_create_a_permission()
    {
        $data = ['name' => $this->faker->name, 'order' => null,];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/permissions', $data)
            ->assertStatus(201)
            ->assertJson(function(AssertableJson $json) use ($data){
                $json
                    ->has('id')
                    ->where('name', $data['name'])
                    ->where('order', $data['order']);
            });

        $this->assertDatabaseHas('permissions', $data);
    }

    /** @test */
    public function it_can_update_a_permission()
    {
        $permission = Permission::factory()->create();

        $update = ['name' => $this->faker->name, 'order' => null,];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/permissions/$permission->id", $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($permission, $update){
                $json
                    ->where('id', $permission->id)
                    ->where('name', $update['name'])
                    ->where('order', $update['order']);
            });

        $this->assertDatabaseHas('permissions', $update);
    }

    /** @test */
    public function it_cannot_update_a_permission()
    {
        $invalidPermissionId = 'invalid';

        $update = ['name' => $this->faker->name, 'order' => null,];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/permissions/$invalidPermissionId", $update)
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_a_permission()
    {
        $permission = Permission::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->deleteJson("/api/permissions/$permission->id")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($permission){
                $json
                    ->where('id', $permission->id)
                    ->where('name', $permission->name)
                    ->where('order', $permission->order);
            });

        $this->assertDatabaseMissing('permissions', $permission->toArray());
    }

    /** @test */
    public function it_cannot_delete_a_permission()
    {
        $invalidPermissionId = 'invalid';

        $this
            ->actingAs($this->user, 'api')
            ->deleteJson("/api/permissions/$invalidPermissionId")
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_log_created_permission()
    {
        $responseData = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/permissions', [
                'name' => $this->faker->name, 
                'order' => null,
            ]);

        $logData = [
            'log_name' => 'permission',
            'description' => 'created',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
    }

    /** @test */
    public function it_can_log_updated_permission()
    {
        $permission = Permission::factory()->create();

        $responseData = $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/permissions/$permission->id", [
                'name' => $this->faker->name, 
                'order' => null,
            ]);

        $logData = [
            'log_name' => 'permission',
            'description' => 'updated',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
    }
}
