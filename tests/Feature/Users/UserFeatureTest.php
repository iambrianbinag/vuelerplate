<?php

namespace Tests\Feature\Users;

use App\Events\Users\UserCreated;
use App\Models\Roles\Role;
use App\Models\Users\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use phpDocumentor\Reflection\Types\This;
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
                    ->where('email', $user->email)
                    ->has('role');
            });
   }

   /** @test */
   public function it_cannot_find_a_user_by_id()
   {
       $invalidUserId = 'invalid';

       $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/$invalidUserId")
            ->assertStatus(404);
   }

   /** @test */
   public function it_can_create_a_user()
   {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret!!!',
            'role_id' => Role::factory()->create()->id
        ];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', $data)
            ->assertStatus(201)
            ->assertJson(function(AssertableJson $json) use ($data){
                $json
                    ->has('id')
                    ->where('name', $data['name'])
                    ->where('email', $data['email'])
                    ->where('role.id', $data['role_id']);
            });
        
        $created = collect($data)->except(['password', 'role_id']);

        $this->assertDatabaseHas('users', $created->all());
   }

   /** @test */
   public function it_can_login_user_after_created()
   {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret!!!',
            'role_id' => Role::factory()->create()->id
        ];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', $data);

        $this->postJson('/api/users/login', [
                'email' => $data['email'],
                'password' => $data['password'],
            ])
            ->assertStatus(200);
   }

   /** @test */
   public function it_can_return_user_from_created_event()
   {
       Event::fake();

       $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => $this->faker->password,
                'role_id' => Role::factory()->create()->id,
            ]);

       Event::assertDispatched(function(UserCreated $event){
            return $event->user instanceof User;
       });
   } 

   /** @test */
   public function it_can_update_a_user()
   {
        $user = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'secret!!!',
                'role_id' => Role::factory()->create()->id
            ]);

        $update = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'role_id' => Role::factory()->create()->id
        ];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/users/{$user['id']}", $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($user, $update){
                $json
                    ->where('id', $user['id'])
                    ->where('name', $update['name'])
                    ->where('email', $update['email'])
                    ->where('role.id', $update['role_id']);
            });

        $updated = collect($update)->except(['role_id']);

        $this->assertDatabaseHas('users', $updated->all());
   }

   /** @test */
   public function it_can_login_user_after_updated()
   {
        $user = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'secret!!!',
                'role_id' => Role::factory()->create()->id,
            ]);

        $update = [
            'name' => 'John Doe',
            'email' => 'johndoeupdated@example.com',
            'password' => 'secretupdated!!!',
            'role_id' => Role::factory()->create()->id,
        ];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/users/{$user['id']}", $update);

        $this->postJson('/api/users/login', [
                'email' => $update['email'],
                'password' => $update['password'],
            ])
            ->assertStatus(200);
   }

   /** @test */
   public function it_cannot_update_a_user()
   {
        $invalidUserId = 'invalid';

        $update = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'role_id' => Role::factory()->create()->id
        ];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/users/$invalidUserId", $update)
            ->assertStatus(404);
   }

   /** @test */
   public function it_can_update_the_account_of_authenticated_user()
   {
        $user = $this->user;

        $update = [
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this
            ->actingAs($user, 'api')
            ->postJson('/api/users/my-account', $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($user, $update){
                $json
                    ->where('id', $user->id)
                    ->where('email', $update['email']);
            });

        $updated = collect($update)->except('password');

        $this->assertDatabaseHas('users', $updated->all());
   }

   /** @test */
   public function it_can_log_in_updated_account_of_authenticated_user()
   {
        $update = [
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users/my-account', $update);

        $this->postJson('/api/users/login', $update)
            ->assertStatus(200);
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
                    ->where('email', $user->email)
                    ->etc();
            });

        $this->assertDatabaseHas('users', $user->toArray());
   }

   /** @test */
   public function it_cannot_soft_delete_a_user()
   {
       $invalidUserId = 'invalid';

       $this
            ->actingAs($this->user, 'api')
            ->deleteJson("/api/users/$invalidUserId")
            ->assertStatus(404);
   }
   
   /** @test */
   public function it_can_log_created_user()
   {
        $responseData = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'secret!!!',
                'role_id' => Role::factory()->create()->id
            ]);

        $logData = [
            'log_name' => 'user',
            'description' => 'created',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
   }

   /** @test */
   public function it_can_log_updated_account_of_authenticated_user()
   {
        $user = $this->user;

        $this
            ->actingAs($user, 'api')
            ->postJson('/api/users/my-account', [
                'email' => $this->faker->unique()->email,
                'password' => $this->faker->password,
            ]);

        $logData = [
            'log_name' => 'user',
            'description' => 'updated',
            'subject_id' => $user->id,
            'causer_id' => $user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
   }

   /** @test */
   public function it_can_log_updated_user()
   {
        $user = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'secret!!!',
                'role_id' => Role::factory()->create()->id
            ]);

        $responseData = $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/users/{$user['id']}", [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'role_id' => Role::factory()->create()->id
            ]);

        $logData = [
            'log_name' => 'user',
            'description' => 'updated',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id
        ];

        $this->assertDatabaseHas('activity_log', $logData);
   }

   /** @test */
   public function it_can_get_the_total_of_user()
   {
        $responseData = $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/total")
            ->assertStatus(200);

       $this->assertDatabaseCount('users', $responseData['total']); 
   }

   /** @test */
   public function it_can_get_the_total_of_user_after_a_user_created()
   {
        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/total");

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'secret!!!',
                'role_id' => Role::factory()->create()->id
            ]);

       $responseData = $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/total");

        $this->assertDatabaseCount("users", $responseData['total']);
   }

   /** @test */
   public function it_can_get_the_total_of_user_after_a_user_soft_deleted()
   {
        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/total");

        $user =  $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/users', [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => 'secret!!!',
                'role_id' => Role::factory()->create()->id
            ]);

        $this
            ->actingAs($this->user, 'api')
            ->deleteJson("/api/users/{$user['id']}");

        $responseData = $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/users/total");


        $this->assertEquals(User::count(), $responseData['total']);
   }
}
