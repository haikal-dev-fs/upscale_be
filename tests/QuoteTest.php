<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class QuoteTest extends TestCase
{
    use DatabaseMigrations;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $payload = [
            'name' => 'Quote User',
            'email' => 'quoteuser@example.com',
            'password' => 'password123'
        ];
        $this->post('/auth/register', $payload);
        $this->token = $this->response->json()['token'];
    }

    public function test_get_quote()
    {
        $this->get('/quote', ['Authorization' => 'Bearer ' . $this->token]);
        $this->seeStatusCode(200);
        $this->seeJsonStructure([[ 'quote', 'author', 'category' ]]);
    }
} 