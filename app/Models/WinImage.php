<?php

namespace App\Models;

use App\User;
use Image;

class WinImage
{
    protected $image;
    protected $width = 4000;

    public function Set($file)
    {
        $this->image = $file;
    }

    public function Get()
    {
        return $this->image;
    }

    public function Resize($width=100,$height=null)
    {
        $image = Image::make($this->image);
        $image->resize($width, $height, function ($aspect){
            $aspect->aspectRatio();
        });
        $image->save();
        $this->width = $width;
    }
    public function upload($folder)
    {
        $user = User::find(auth()->id());
        $this->image->store($folder.'/'.$this->width);
        Photo::Create([
            'user_id' => $user->id,
            'folder' => $folder.'/'.$this->width,
            'name' => $this->image->hashName(),
        ]);
    }
}
