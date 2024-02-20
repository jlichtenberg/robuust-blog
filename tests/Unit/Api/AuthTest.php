<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    /** @test */
    public function registers_a_new_user()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        // Make the request
        $response = $this->postJson('/api/register', $data);

        // Assert HTTP status code
        $response->assertStatus(200);

        // Assert user is stored in the database
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        // delete the user
        User::where('email', 'john@example.com')->delete();
    }

}
