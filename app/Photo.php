<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{
  /**
   * リレーション - likesテーブル中間テーブルとなる
   * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
   */
  public function likes()
  {
    return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    // 外部キーしかないモデルクラスは作成する必要がない
    // withTimestampsはcreated_atとupdated_atを更新するための指定
  }


  /**
   * リレーションシップ - usersテーブル
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function owner()
  {
    return $this->belongsTo('App\User', 'user_id', 'id', 'users');
  }

  /**
   * リレーション - commentsテーブル
   * @return Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function comments()
  {
    return $this->hasMany('App\Comment')->orderBy('id', 'desc');
  }
  
  /**
   * アクセサ - url
   * @return string
   */

  public function getUrlAttribute()
  {
    //urlメソッドはS3上のファイルの公開URLを返却する
    return Storage::cloud()->url($this->attributes['filename']);
  }

  /**
   * アクセサ likes_count
   * @return int
   */
  public function getLikesCountAttribute()
  {
    return $this->likes->count();
  }

  /**
   * アクセサ liked_by_user
   * @return boolean
   */
  public function getLikedByUserAttribute()
  {
    if(Auth::guest()){
      return false;
    }
    return $this->likes->contains(function ($user){
      return $user->id === Auth::user()->id;
    });
  }


  /** JSONに含める属性
   * 明示的に指定してあげないとレスポンスデータには含まれない
   */
  protected $appends = [
    'url','likes_count', 'liked_by_user'
  ];

  /**
   * Jsonに含める属性で、指定した情報のみJsonデータに含め、レスポンスデータとして返ってくる
   */
  protected $visible = [
    'id', 'url', 'owner', 'comments', 'likes_count', 'liked_by_user'
  ];

    /** プライマリキーの型 */
    protected $keyType = 'string';

    /** IDの桁数 */
    const ID_LENGTH = 12;

    /**一ページあたりに表示する件数 */
    protected $perPage = 6;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @return string
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }
}