<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Support\Facades\DB;
use DB;
use App\User;
use Storage;

class Photo extends Model
{
    protected $table='photos';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $dir = 'public/images';
    protected $thumb_dir = 'public/thumbnails';

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function Stores()
    {
        return $this->hasMany('App\Models\PhotoStore');
    }

    /*
     * 写真の登録
     * 第１引数にファイル、第２引数に保存先を指定します
     */
    public function Upload($file)
    {
        //画像登録
        $win = new WinImage();
        $win->set($file);
        $win->upload();

    }
    /*
     * 登録されている写真（元画像、サムネイル、データベース）を削除する
     */
    public function allDelete($id)
    {
        $photo = Photo::find($id);
        $stores = $photo->stores;
        foreach($stores as $store){
            Storage::delete($store->dir . '/' . $store->photo->name);
            Storage::delete($store->thumb_dir . '/' . $store->photo->name);
        }
        PhotoStore::where('photo_id',$photo->id)->delete();
        Photo::find($id)->delete();
    }
    /*
     * 画像を回転させる
     */
    public function Turn($id)
    {
        $photo = Photo::find($id);
        $url = $this->dir . '/' . $photo->name;
        $image = Storage::get($url);
        $win = new WinImage();
        $win->set($image);
        $win->setName($photo->name);
        $win->turn($id);
        Storage::put($url, $win);
        $thumb = $this->thumb_dir.'/'.$photo->name;
        $win->set($thumb);
        $win->turn($id);
        Storage::put($thumb, $win);
    }
    /*
     * photoのidから元画像を取得する
     */
    public function getBasePhoto($id=null)
    {
        if(is_null($id)){
            $id = $this->id;
        }
        $store = PhotoStore::where('photo_id',$id)
            ->where('dir',$this->dir)
            ->first();
        return $store;
    }

}
