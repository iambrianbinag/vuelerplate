<?php

namespace Tests\Feature\Settings;

use App\Models\Settings\Setting;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SettingFeatureTest extends TestCase
{
    /** @test */
    public function it_can_return_all_settings()
    {
        Setting::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson('/api/settings')
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->first(function($json){
                        $json
                            ->has('id')
                            ->has('name')
                            ->has('description')
                            ->has('value');
                    });
            });
    }

    /** @test */
    public function it_can_find_a_setting_by_id()
    {
        $setting = Setting::factory()->create();

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/settings/$setting->id")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($setting){
                $json
                    ->where('id', $setting->id)
                    ->where('name', $setting->name)
                    ->where('description', $setting->description)
                    ->where('value', $setting->value);
            });
    }

    /** @test */
    public function it_cannot_find_a_setting_by_id()
    {
        $invalidSettingId = 'invalid';

        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/settings/$invalidSettingId")
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_create_a_setting()
    {
        $data = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence, 
            'value' => $this->faker->name,
        ];

        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/settings', $data)
            ->assertStatus(201)
            ->assertJson(function(AssertableJson $json) use ($data){
                $json
                    ->has('id')
                    ->where('name', $data['name'])
                    ->where('description', $data['description'])
                    ->where('value', $data['value']);
            });

        $this->assertDatabaseHas('settings', $data);
    }

    /** @test */
    public function it_can_update_a_setting()
    {
        $setting = Setting::factory()->create();

        $update = [
            'name' => $this->faker->name,
            'description' => $this->faker->sentence,  
            'value' => $this->faker->name,
        ];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/settings/$setting->id", $update)
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json) use ($setting, $update){
                $json
                    ->where('id', $setting->id)
                    ->where('name', $update['name'])
                    ->where('description', $update['description'])
                    ->where('value', $update['value']);
            });

        $this->assertDatabaseHas('settings', $update);
    }

    /** @test */
    public function it_cannot_update_a_setting()
    {
        $invalidSettingId = 'invalid';

        $update = [
            'name' => $this->faker->name, 
            'description' => $this->faker->sentence, 
            'value' => $this->faker->name,
        ];

        $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/settings/$invalidSettingId", $update)
            ->assertStatus(404);
    }

    /** @test */
    public function it_can_log_created_setting()
    {
        $responseData = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/settings', [
                'name' => $this->faker->name, 
                'description' => $this->faker->sentence, 
                'value' => $this->faker->name,
            ]);

        $logData = [
            'log_name' => 'setting',
            'description' => 'created',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id,
        ];

        $this->assertDatabaseHas('activity_log', $logData);
    }

    /** @test */
    public function it_can_log_updated_setting()
    {
        $setting = Setting::factory()->create();

        $responseData = $this
            ->actingAs($this->user, 'api')
            ->putJson("/api/settings/$setting->id", [
                'name' => $this->faker->name, 
                'description' => $this->faker->sentence, 
                'value' => $this->faker->name,
            ]);

        $logData = [
            'log_name' => 'setting',
            'description' => 'updated',
            'subject_id' => $responseData['id'],
            'causer_id' => $this->user->id,
        ];
    
        $this->assertDatabaseHas('activity_log', $logData);
    }
}
