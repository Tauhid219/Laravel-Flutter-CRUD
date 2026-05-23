<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration via API.
     */
    public function test_user_can_register_via_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'token',
                         'user' => ['id', 'name', 'email']
                     ]
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe'
        ]);
    }

    /**
     * Test user login via API.
     */
    public function test_user_can_login_via_api()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'jane@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'token',
                         'user' => ['id', 'name', 'email']
                     ]
                 ]);
    }

    /**
     * Test unauthenticated access to task endpoints.
     */
    public function test_unauthenticated_user_cannot_access_tasks_api()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    /**
     * Test authenticated task CRUD.
     */
    public function test_authenticated_user_can_crud_tasks_via_api()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        // 1. Create Task
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/tasks', [
            'title' => 'API Task',
            'description' => 'Created via API',
            'category' => 'Work',
            'priority' => 'High',
            'due_date' => '2026-06-01',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.title', 'API Task')
                 ->assertJsonPath('data.priority', 'High');

        $taskId = $response->json('data.id');

        // 2. Read Task
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');

        // 3. Update Task
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->putJson("/api/tasks/{$taskId}", [
            'title' => 'Updated API Task',
            'is_completed' => true
        ]);

        $response->assertStatus(200)
                 ->assertJsonPath('data.title', 'Updated API Task')
                 ->assertJsonPath('data.is_completed', true);

        // 4. Delete Task
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->deleteJson("/api/tasks/{$taskId}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tasks', [
            'id' => $taskId
        ]);
    }
}
