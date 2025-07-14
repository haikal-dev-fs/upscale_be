<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $payload = [
            'name' => 'Task User',
            'email' => 'taskuser@example.com',
            'password' => 'password123'
        ];
        $this->post('/auth/register', $payload);
        $this->token = $this->response->json()['token'];
    }

    public function test_create_task()
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'Test Description'
        ];
        $this->post('/tasks', $payload, ['Authorization' => 'Bearer ' . $this->token]);
        if (!array_key_exists('status', $this->response->json())) {
            fwrite(STDERR, json_encode($this->response->json()));
        }
        $this->seeStatusCode(201);
        $this->seeJsonStructure(['id', 'title', 'description', 'status', 'user_id']);
    }

    public function test_list_tasks()
    {
        $this->test_create_task();
        $this->get('/tasks', ['Authorization' => 'Bearer ' . $this->token]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['data']);
    }

    public function test_update_task()
    {
        $this->test_create_task();
        $taskId = $this->response->json()['id'];
        $payload = [
            'title' => 'Updated Task',
            'status' => 'done'
        ];
        $this->patch("/tasks/{$taskId}", $payload, ['Authorization' => 'Bearer ' . $this->token]);
        $this->seeStatusCode(200);
        $this->seeJsonContains(['title' => 'Updated Task', 'status' => 'done']);
    }

    public function test_delete_task()
    {
        $this->test_create_task();
        $taskId = $this->response->json()['id'];
        $this->delete("/tasks/{$taskId}", [], ['Authorization' => 'Bearer ' . $this->token]);
        $this->seeStatusCode(200);
        $this->seeJsonContains(['message' => 'Task deleted']);
    }
} 