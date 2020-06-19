<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
  // jsonに含める属性を定義
  protected $visible = [
    'author', 'content'
  ];

  /**
   * リレーション - usersテーブル
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function author()
  {
    return $this->belongsTo('App\User', 'user_id', 'id', 'users');
  }
}
