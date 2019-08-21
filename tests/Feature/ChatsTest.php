<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    /**
     * A test to ensure message sending functionality
     *
     * @return void
     */
    public function testSendMessage()
    {
      $response = $this->post('/message', ['message' => 'test','name' => 'Dan']);
      $response->assertStatus(200);
    }
    /**
     * A test to ensure message fetching functionality
     *
     * @return void
     */
    public function testFetchMessages()
    {
      $response = $this->get('/messages/Dan');
      $response->assertStatus(200);
    }
}
