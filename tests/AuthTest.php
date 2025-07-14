<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function test_register_success()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ];
        $this->post('/auth/register', $payload);
        if ($this->response->getStatusCode() !== 201) {
            fwrite(STDERR, $this->response->getContent());
        }
        $this->seeStatusCode(201);
        $this->seeJsonStructure(['token']);
    }

    public function test_register_duplicate_email()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123'
        ];
        $this->post('/auth/register', $payload);
        $this->post('/auth/register', $payload);
        if ($this->response->getStatusCode() !== 422) {
            fwrite(STDERR, $this->response->getContent());
        }
        $this->seeStatusCode(422);
        $this->seeJsonContains(['error' => 'The email has already been taken.']);
    }

    public function test_login_success()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'testlogin@example.com',
            'password' => 'password123'
        ];
        $this->post('/auth/register', $payload);
        $login = [
            'email' => 'testlogin@example.com',
            'password' => 'password123'
        ];
        $this->post('/auth/login', $login);
        if ($this->response->getStatusCode() !== 200) {
            fwrite(STDERR, $this->response->getContent());
        }
        $this->seeStatusCode(200);
        $this->seeJsonStructure(['token']);
    }

    public function test_login_invalid_credentials()
    {
        $login = [
            'email' => 'notfound@example.com',
            'password' => 'wrongpass'
        ];
        $this->post('/auth/login', $login);
        if ($this->response->getStatusCode() !== 401) {
            fwrite(STDERR, $this->response->getContent());
        }
        $this->seeStatusCode(401);
        $this->seeJsonContains(['error' => 'invalid_credentials']);
    }
} 