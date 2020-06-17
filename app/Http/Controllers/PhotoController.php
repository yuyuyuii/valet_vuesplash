<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhoto;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function __construct()
    {
      //認証が必要
      $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePhoto  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePhoto $request)
    {
      //投稿写真の拡張子を取得
      $extension = $request->photo->extension();
      $photo = new Photo();

      //インスタンス生成時に割り振られたランダムなID値と本来の拡張子を組み合わせてファイル名とする
      $photo->filename = $photo->id . '.'.$extension;

      //s3に保存する
      // 第三引数の'public'はファイルを公開状態で保存する為、必要
      // cloud() を呼んだ場合は config/filesystems.php の cloud の設定にしたがって使用されるストレージが決まります。      
      Strage::cloud() //
      ->putFileAs('', $request->photo, $photo->filename, 'public');

      // データベースエラー時にファイル削除を行う為、トランザクションを利用する
      DB::beginTransaction();
      try{
        Auth::user()->photos()->save($photo);
        DB::commit();
      }catch(\Exception $exception){
        DB::rollBack();
        // DBとの不整合を避ける為アップロードしたファイルを削除
        Strage::cloud()->delete($photo->filename);
        throw $exception;
      };
      //リソースの新規作成なので、レスポンスコードは201(CREATED)を返却する
      return response($photo, 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
