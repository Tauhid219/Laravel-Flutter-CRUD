<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_task_via_web(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Test Task Web',
            'description' => 'Test Description Web',
            'category' => 'Work',
            'priority' => 'High',
            'due_date' => '2026-05-30',
        ]);

        $response->assertRedirect('/dashboard');
        
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task Web',
            'category' => 'Work',
            'priority' => 'High',
            'user_id' => $user->id,
        ]);
    }
}
