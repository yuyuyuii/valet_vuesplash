<?php

namespace Tests\Feature;


use App\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PhotoDetailApiTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @test
   */
  public function should_正しい構造のjsonデータを返却する()
  {
    //testデータを作成
    factory(Photo::class)->create()->each(function($photo){
      $photo->comments()->saveMany(factory(Comment::class, 3)->make());
    });
    //登録したデータを取得
    $photo = Photo::first();
    //photoの詳細情報取得アクションにアクセスし、レスポンスデータを取得
    $response = $this->json('get', route('photo.show',[
      'id' => $photo->id,
    ]));
    
    $response->assertStatus(200)
    //assertJsonFragment()でJsonのフォーマットを確かめる
            ->assertJsonFragment([
              'id' => $photo->id,
              'url' => $photo->url,
              'owner' => [
                'name' => $photo->owner->name,
              ],
              'comments' => $photo->comments
                ->sortByDesc('id')
                ->map(function($comment){
                  return [
                    'author' => [
                      'name' => $comment->owner->name,
                    ],
                    'content' => $comment->content,
                  ];
                })
                ->all(),
            ]);
  }

}
