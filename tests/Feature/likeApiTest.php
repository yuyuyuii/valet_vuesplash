<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Photo;

class likeApiTest extends TestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {
    parent::setUp();
    // ユーザーを作成
    $this->user = factory(User::class)->create();
    // 写真を作成し、取得
    factory(Photo::class)->create();
    $this->photo = Photo::first();
  }

  /**
   * @test
   */
  public function should_いいねを追加できる()
  {
    $response = $this->actingAs($this->user)
      ->json('put', route('photo.like',[
        'id' => $this->photo->id,
      ]));

      $response->assertStatus(200)
        ->assertJsonFragment([
          'photo_id' => $this->photo->id,
        ]);
      $this->assertEquals(1, $this->photo->likes()->count());
  }

  /**
   * @test
   */
  public function should_2回いいねを押しても1個しかいいねがつかない()
  {
    $param = ['id' =>$this->photo->id];
    $this->actingAs($this->user)->json('put', route('photo.like', $param));
    $this->actingAs($this->user)->json('put', route('photo.like', $param));

    $this->assertEquals(1, $this->photo->likes()->count());
  }

  /**
   * @test
   */
  public function should_いいねを取り消す事ができる()
  {
    $this->photo->likes()->attach($this->user->id);

    $response = $this->actingAs($this->user)
    ->json('delete', route('photo.like', [
      'id' => $this->photo->id
    ]));

    $response->assertStatus(200)
      ->assertJsonFragment([
        'photo_id' => $this->photo->id,
      ]);
    $this->assertEquals(0, $this->photo->likes()->count());
  }
  
}  
