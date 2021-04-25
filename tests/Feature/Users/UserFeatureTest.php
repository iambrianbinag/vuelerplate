<?php

namespace Tests\Feature\Users;

use App\Models\Users\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserFeatureTest extends TestCase
{
   /** @test */
   public function it_can_return_all_users()
   {
        User::factory(5)->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/users')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('data', 6, function($json){ // +1 in the TestCase
                        $json
                            ->has('id')
                            ->has('name')
                            ->has('email');
                    }) 
                    ->etc();
            });
   }

   /** @test */
   public function it_can_search_users()
   {
        $authUser = $this->user; // From TestCase

        $this
            ->actingAs($authUser, 'api')
            ->getJson("/api/users?search=$authUser->name")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($authUser){
                $json
                    ->has('data', 1, function($json) use ($authUser){
                        $json
                            ->where('id', $authUser->id)
                            ->where('name', $authUser->name)
                            ->where('email', $authUser->email);
                    })
                    ->etc();
            });
   }

   /** @test */
   public function it_can_find_a_user_by_id()
   {
        $user = User::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/$user->id") 
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($user){
                $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('email', $user->email);
            });
   }

   /** @test */
   public function it_can_create_a_user()
   {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret!!!'
        ];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', $data)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($data){
                $json
                    ->has('id')
                    ->where('name', $data['name'])
                    ->where('email', $data['email']);
            });
        
        $created = collect($data)->except('password');

        $this->assertDatabaseHas('users', $created->all());
   }

   /** @test */
   public function it_can_update_a_user()
   {
        $user = User::factory()->create();

        $update = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/users/$user->id", $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($user, $update){
                $json
                    ->where('id', $user->id)
                    ->where('name', $update['name'])
                    ->where('email', $update['email']);
            });

        $this->assertDatabaseHas('users', $update);
   }

   /** @test */
   public function it_can_soft_delete_a_user()
   {
        $user = User::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->deleteJson("/api/users/$user->id")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($user){
                $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('email', $user->email);
            });

        $this->assertDatabaseHas('users', $user->toArray());
   } 
}