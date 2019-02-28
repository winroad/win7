<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Storage;

class Photo extends Model
{
    protected $table='photos';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $folder = 'public/images';
    protected $thumb_folder = 'public/thumbnails';

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeThumbnail($query)
    {
        $user = User::find(auth()->id());
        $query->where('user_id',$user->id);
        $query->where('folder',$this->thumb_folder);
        return $query;
    }
    /*
     * 写真の登録
     * 第１引数にファイル、第２引数に保存先を指定します
     */
    public function Upload($file)
    {
        $user = User::find(auth()->id());
        //元画像登録
        $win = new WinImage();
        $img = $win->set($file);
        $img->store($this->folder);
        Photo::Create([
            'user_id' => $user->id,
            'folder' => $this->folder,
            'name' => $img->hashName(),
        ]);
        //サムネイルの登録
        $win->thumbnail();
        $img->store($this->thumb_folder);
        Photo::Create([
            'user_id' => $user->id,
            'folder' => $this->thumb_folder,
            'name' => $img->hashName(),
        ]);
    }
    /*
     * 登録されている写真（元画像、サムネイル、データベース）を削除する
     */
    public function allDelete($id)
    {
        $photo = Photo::find($id);
        Storage::delete($this->folder . '/' . $photo->name);
        Storage::delete($this->thumb_folder . '/' . $photo->name);
        Photo::where('name',$photo->name)->delete();
    }
    /*
     * 画像を回転させる
     */
    public function Turn($id)
    {
        $photo = Photo::find($id);
        $file = url($this->folder . '/' . $photo->name);
        $win = new WinImage();
        $win->set($file);
        $win->turn();
        $win->store($this->folder);
        $thumb = $this->thumb_folder.'/'.$photo->name;
        $win->set($thumb);
        $win->store($this->thumb_folder);
    }

}
