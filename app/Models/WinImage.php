<?php

namespace App\Models;

use App\User;
use Image;

class WinImage
{
    protected $image;
    protected $width='full';

    public function Set($file)
    {
        $this->image = $file;
    }

    public function Get()
    {
        return $this->image;
    }

    public function Resize($width=100)
    {
        $image = Image::make($this->image);
        $image->resize($width, null, function ($aspect){
            $aspect->aspectRatio();
        });
        $image->save();
        $this->width = $width;
    }
    public function save($folder)
    {
//        $image = Image::make($this->image);
        $user = User::find(auth()->id());
        $this->image->store($folder.'/'.$this->width);
        Photo::Create([
            'user_id' => $user->id,
            'folder' => $folder,
            'name' => $this->width.'/'.$this->image->hashName(),
        ]);
    }
}
