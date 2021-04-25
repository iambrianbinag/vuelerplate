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
                            ->has('name');
                    })
                    ->etc();
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
                            ->has('name');
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
                    ->where('name', $permission->name);
            });
    }

    /** @test */
    public function it_can_create_a_permission()
    {
        $data = ['name' => $this->faker->name];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/permissions', $data)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($data){
                $json
                    ->has('id')
                    ->where('name', $data['name']);
            });

        $this->assertDatabaseHas('permissions', $data);
    }

    /** @test */
    public function it_can_update_a_permission()
    {
        $permission = Permission::factory()->create();

        $update = ['name' => $this->faker->name];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/permissions/$permission->id", $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($permission, $update){
                $json
                    ->where('id', $permission->id)
                    ->where('name', $update['name']);
            });

        $this->assertDatabaseHas('permissions', $update);
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
                    ->where('name', $permission->name);
            });

        $this->assertDatabaseMissing('permissions', $permission->toArray());
    }
}
