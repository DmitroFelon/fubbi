<?php

namespace Tests\Feature;

use Tests\TestCase;

class ThriveCartTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRouter()
    {
        $response = $this->post('thrivecart');

        $response->assertStatus(200);
    }
}
