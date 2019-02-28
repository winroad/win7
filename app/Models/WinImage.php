<?php

namespace App\Models;

use App\User;
use Image;

class WinImage
{
    protected $image;
    protected $width;

    public function Set($file)
    {
        $this->image = $file;
        return $this->image;
    }

    public function Get()
    {
        return $this->image;
    }

    /*
     * サムネイル画像を作る
     */
    public function Thumbnail($size=100)
    {
        $image = Image::make($this->image);
        $width = $image->width();
        $height = $image->height();
        if($width > $height){
            $image->crop($height, $height);
        } else {
            $image->crop($width, $width);
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

    public function Upload($folder)
    {
        $user = User::find(auth()->id());
        $this->image->store($folder.'/'.$this->width);
        Photo::Create([
            'user_id' => $user->id,
            'folder' => $folder.'/'.$this->width,
            'name' => $this->image->hashName(),
        ]);
    }
    /*
     * 画像を回転させる
     */
    public function Turn()
    {
        $image = Image::make($this->image);
        $image->rotate(270);
        $image->save();
    }
}
