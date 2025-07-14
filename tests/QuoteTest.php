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
        $json = $this->response->json();
        fwrite(STDERR, json_encode($json));
        $this->assertIsArray($json);
        if (count($json) > 0 && is_array($json[0])) {
            $this->assertArrayHasKey('quote', $json[0]);
            $this->assertArrayHasKey('author', $json[0]);
            $this->assertArrayHasKey('category', $json[0]);
        }
    }
} 