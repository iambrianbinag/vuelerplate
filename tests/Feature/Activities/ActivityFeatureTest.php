<?php

namespace Tests\Feature\Activities;

use App\Events\Activities\ActivityCreated;
use App\Models\Activities\Activity;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ActivityFeatureTest extends TestCase
{
    /** @test */
    public function it_can_return_all_log()
    {   
        $permissionId = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/permissions', [
                'name' => $this->faker->name, 
                'order' => null,
            ])['id'];
        
        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/activity-log/?log_name=permission&subject_id=$permissionId")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('data')
                    ->has('data.0', function($json){
                        $json
                            ->has('id')
                            ->has('log_name')
                            ->has('description')
                            ->has('created_at')
                            ->has('updated_at')
                            ->has('changes')
                            ->has('subject')
                            ->has('causer');
                    })
                    ->etc();
            });
    }

    /** @test */
    public function it_can_return_all_log_not_paginated()
    {   
        $permissionId = $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/permissions', [
                'name' => $this->faker->name, 
                'order' => null,
            ])['id'];
        
        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/activity-log/?log_name=permission&subject_id=$permissionId&not_paginated=1")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->first(function($json){
                        $json
                            ->has('id')
                            ->has('log_name')
                            ->has('description')
                            ->has('created_at')
                            ->has('updated_at')
                            ->has('changes')
                            ->has('subject')
                            ->has('causer');
                    });
            });
    }

    /** @test */
    public function it_can_search_log()
    {   
        $this
            ->actingAs($this->user, 'api')
            ->postJson('/api/permissions', [
                'name' => $this->faker->name, 
                'order' => null,
            ]);
        
        $this
            ->actingAs($this->user, 'api')
            ->getJson("/api/activity-log/?search=permission")
            ->assertStatus(200)
            ->assertJson(function(AssertableJson $json){
                $json
                    ->has('data')
                    ->has('data.0', function($json){
                        $json
                            ->has('id')
                            ->has('log_name')
                            ->has('description')
                            ->has('created_at')
                            ->has('updated_at')
                            ->has('changes')
                            ->has('subject')
                            ->has('causer');
                    })
                    ->etc();
            });
    }

    /** @test */
    public function it_can_dispatch_created_event_of_activity()
    {
        Event::fake();

        Activity::factory()->create();

        Event::assertDispatched(function(ActivityCreated $event){
            return $event->activity instanceof Activity;
        });
    }
}
