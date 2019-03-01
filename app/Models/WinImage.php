<?php

namespace App\Models;

use App\User;
use Image;

class WinImage
{
    protected $image;
    protected $dir = 'public/images';
    protected $thumb_dir = 'public/thumbnails';

    public function Set($file)
    {
        $this->image = $file;
        return $this->image;
    }

    public function Get()
    {
        return $this->image;
    }

    public function SetName($hashName)
    {
        $this->hashName = $hashName;
        return $this->hashName;
    }

    /*
     * サムネイル画像を作る
     */
    public function Thumbnail($size=100)
    {
        $image = Image::make($this->image);
//        $this->width = $image->width();
//        $this->height = $image->height();
        //縦幅が小さければ、縦幅に合わせてクロップします
        if($image->width() > $image->height()){
            $image->crop($image->height(), $image->height());
        } else {
            //横幅が小さければ横幅に合わせてクロップします
            $image->crop($image->width(), $image->width());
        }
        $image->resize($size, $size, function ($aspect){
            $aspect->aspectRatio();
        });
        $image->save();
    }
    /*
     * リサイズする
     */
    public function Resize($width=100)
    {
        $image = Image::make($this->image);
        $image->resize($width, null, function ($aspect){
            $aspect->aspectRatio();
        });
        $image->save();
        $this->width = $width;
    }

    /*
     * 画像をアップロードする
     */
    public function Upload()
    {
        $user = User::find(auth()->id());
        $this->image->store($this->dir);
        $win = Image::make($this->image);
        $photo = Photo::firstOrCreate([
            'user_id' => $user->id,
            'name' => $this->image->hashName(),
        ]);
        //元画像の登録
        PhotoStore::firstOrCreate([
            'user_id' => $user->id,
            'photo_id' => $photo->id,
            'dir' => $this->dir,
            'width' => $win->width(),
            'height' => $win->height(),
            'size' => $win->filesize(),
            'mime' => $win->mime(),
        ]);
        //サムネイル画像の登録
        $this->Thumbnail();
        $thumb = Image::make($this->image);
        $this->image->store($this->thumb_dir);
        PhotoStore::firstOrCreate([
            'user_id' => $user->id,
            'photo_id' => $photo->id,
            'dir' => $this->thumb_dir,
            'width' => $thumb->width(),
            'height' => $thumb->height(),
            'size' => $thumb->filesize(),
            'mime' => $win->mime(),
        ]);
    }
    /*
     * 画像を回転させる
     */
    public function Turn($id)
    {
        $photo = Photo::find($id);
        dd($photo->folder);
        //        dd(storage_path($url));
        $image = Image::make($this->image);
        $image->filename = $photo->name;
        $image->dirname = $photo->folder;
        $image->path = '/tmp';
        $image->writable = true;
        $image->readable = true;
//        dd($image);
        $image->rotate(90);
        dd($image);
        $image->save($image->dirname);
//        $image->filename = 'test_file';
//        dd($image);
//        dd($image);
//        dd($image->filesize());
//        $image->save();
    }
}
