<?php

namespace Tests\Feature\Auth;

use App\Models\Users\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserAuthFeatureTest extends TestCase
{
    /** @test */
    public function it_can_login_user()
    {
        $user = User::factory()->create(['password' => bcrypt('secret!!!')]);

        $this->postJson('/api/users/login', [
            'email' => $user->email,
            'password' => 'secret!!!'
        ])
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('access_token')
                    ->has('token_type')
                    ->has('expires_in');
            }); 
    }

    /** @test */
    public function it_cannot_login_user()
    {
        $this->postJson('/api/users/login', [
            'email' => 'invalid@example.com',
            'password' => 'invalid'
        ])
            ->assertStatus(403)
            ->assertExactJson([
                'message' => 'Invalid email or password'
            ]);
    }

    /** @test */
    public function it_can_logout_user()
    {
        $user = User::factory()->create(['password' => bcrypt('secret!!!')]);

        $this->postJson('/api/users/login', [
            'email' => $user->email,
            'password' => 'secret!!!'
        ]);

        $this->postJson('/api/users/logout')
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'Successfully logged out'
            ]);
    }

    /** @test */
    public function it_can_refresh_user_token()
    {
        $user = User::factory()->create(['password' => bcrypt('secret!!!')]);

        $responseOfLogin = $this->postJson('/api/users/login', [
            'email' => $user->email,
            'password' => 'secret!!!'
        ]);

        $responseOfRefresh = $this->postJson('/api/users/refresh')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('access_token')
                    ->has('token_type')
                    ->has('expires_in');
            });
        
        $this->assertNotEquals($responseOfLogin['access_token'], $responseOfRefresh['access_token']);
    }

    /** @test */
    public function it_cannot_logout_user()
    {
        $this->postJson('/api/users/logout')
            ->assertStatus(401);
    }

    /** @test */
    public function it_can_get_authenticated_user_info()
    {
        $user = User::factory()->create();

        $this->postJson('/api/users/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $this
            ->actingAs($user, 'api')
            ->postJson('/api/users/me')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($user){
                $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('email', $user->email)
                    ->where('role', $user->role);
            });
    }
}
