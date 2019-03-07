<?php

namespace App\Models;

use App\User;
use Image;
use Storage;

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
     * 個人所有のサムネイルを取得する
     */
    public function getMyThumbs($user,$num)
    {
        $thumbs = PhotoStore::where('user_id',$user->id)
            ->where('dir',$this->thumb_dir)
            ->paginate($num);
        return $thumbs;
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
    public function Turn($id,$angle)
    {
        $store = PhotoStore::find($id);
        //元画像を回転させる
        $storage = Storage::get($store->dir.'/'.$store->photo->name);
        $image = Image::make($storage);
        $this->set($storage);
        $image->filename = $store->photo->name;
        $image->dirname = $store->dir;
        $image->path = '/tmp';
        $image->writable = true;
        $image->readable = true;
        $image->rotate($angle);
        $image->save('storage/images/'.$store->photo->name);
        $store->width = $image->width();
        $store->height = $image->height();
        $store->save();

        //サムネイル画像を回転させる
        $thumb = PhotoStore::where('photo_id',$store->photo_id)
            ->where('dir',$this->thumb_dir)
            ->first();
        $storage2 = Storage::get($this->thumb_dir.'/'.$thumb->photo->name);
        $image2 = Image::make($storage2);
        $this->set($storage2);
        $image2->filename = $thumb->photo->name;
        $image2->dirname = $thumb->dir;
        $image2->path = '/tmp';
        $image2->writable = true;
        $image2->readable = true;
        $image2->rotate($angle);
        $image2->save('storage/thumbnails/'.$thumb->photo->name);
        $thumb->width = $image2->width();
        $thumb->height = $image2->height();
        $thumb->save();
    }
}
