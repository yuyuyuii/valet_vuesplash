<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Photo;
use App\User;

class AddCommentApiTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */

    public function setUp(): void
    {
      parent::setUp();
      //factoryを使ってテストユーザー作成
      $this->user = factory(User::class)->create();
    }
    
    /**
     * @test
     */

    public function should_コメントを投稿できる()
    {
      //写真データを作成
      factory(Photo::class)->create();
      // 一件取得
      $photo = Photo::first();
      // contentを作成
      $content = "sample comment";
      // コメントを送信
      // テストユーザーがポストでphoto/commentに対して、photoのコメントを送る
      $response = $this->actingAs($this->user)
        ->json('post', route('photo.comment',[
          'photo' => $photo->id,
        ]), compact('content'));
      // コメントを受け取り
      $comments = $photo->comment->get();

      //ステータスコードの確認
      $this->assertStatus(201)
      //jsonのフォーマットが期待通りである事
          ->assertJsonFragment([
            'autor' => [
              'name' => $this->user->name,
            ],
            'content' => $content
          ]);

          //DBにコメントが一件登録されている事
          $this->assertEquals(1, $comment->count());
          //内容がAPIでリクエストしたものである事
          $this->assertEquals($content, $comments[0]->content);
    }
}
