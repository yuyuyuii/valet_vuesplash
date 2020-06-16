<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class RegisterApiTest extends TestCase
{
  /**
   * @test
   */

  public function shoud_新しいユーザーを作成し返却()
  {
    $data = [
      'name' => 'test_user',
      'email' => 'test@gmail.com',
      'password' => 'test1234',
      'password_confirmation' => 'test1234'
    ];

    $response = $this->json('POST', route('register'), $data);

    $user = User::first();
    $this->assertEquals($data['name'], $user->name);

    $response
      ->assertStatus(201)
      ->assertJson(['name' => $user->name]);
  }
}
