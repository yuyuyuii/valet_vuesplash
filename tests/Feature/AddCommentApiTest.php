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

    public function setUp(): void
    {
        parent::setUp();

        // テストユーザー作成
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
      $comments = $photo->comments()->get();

      //ステータスコードの確認
      $response->assertStatus(201)
            // JSONフォーマットが期待通りであること
            ->assertJsonFragment([
                "author" => [
                    "name" => $this->user->name,
                ],
                "content" => $content,
            ]);
        // DBにコメントが1件登録されていること
        $this->assertEquals(1, $comments->count());
        // 内容がAPIでリクエストしたものであること
        $this->assertEquals($content, $comments[0]->content);
    }
}
