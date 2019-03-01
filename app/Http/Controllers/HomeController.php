<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\WinImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::find(auth()->id());
        $photo = new Photo();
        $thumbs = $photo->getMyThumbnails();
        return view('home',compact('user','thumbs'));
    }
    /*
     * 画像のアップロード
     * アップロード時に元画像とサムネイル画像を登録する
     */
    public function upload(Request $request)
    {
        $this->validate($request,[
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png',
                'dimensions:min_width=100,min_height=100,max_width=5000,max_height=5000',
            ]
        ]);
        if($request->file('file')->isValid([])){
            $file = $request->file;
//            dd($file);
            $photo = new Photo();
            $photo->Upload($file);
            return redirect('/home')->with('success', '保存しました');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['file' => '画像がアップされていないか不正なデータです。']);
        }
    }
    /*
     * 画像の表示
     */
    public function photoview($id)
    {
        $photo = Photo::find($id);
        $base = $photo->getBasePhoto();
//        dd(asset($base->dir.'/'.$base->photo->name));
        return view('photos.view',compact('base'));
    }
    /*
     * 画像の削除
     */
    public function photoDelete($id)
    {
        $photo = Photo::find($id);
        $photo->allDelete($id);
        return redirect('home');
    }
    /*
     * 画像の回転
     */
    public function photoTurn($id)
    {
        $photo = Photo::find($id);
        $photo->turn($id);
        return redirect('home');
    }

    /*
     * アバターのアップロード処理
     *
     *
    public function upload(Request $request)
    {
      $this->validate($request,[
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png',
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
            ]
        ]);
        if($request->file('file')->isValid([])){
            $avatar = $request->file;
            $photo = new Photo();
            $photo->Upload($avatar);


            $img = new WinImage();
            $img->set($avatar);
            $img->resize(100);
            $img->upload('public/images');

//            $avatar = WinImage::get($request->file);
//            dd($avatar);
//            $win->upload($avatar);
            $img = WinImage::get($avatar);
//            $img300 = Image::make($avatar);
//            $img->fit(100)->save();
            $img->resize(100, null, function ($aspect){
                $aspect->aspectRatio();
            });
            $img->save();
            $avatar->store('public/images');
            $img2 = WinImage::get($avatar);
            $img2->resize(300, null, function ($aspect){
                $aspect->aspectRatio();
            });
            $img2->save();
            $avatar->store('public/images');
            $file = $request->file->store('public/avatar');
            $user = User::find(auth()->id());
            //以前のファイルの削除
            if(!is_null($user->avatar_filename)){
                $old = 'public/avatar/'.$user->avatar_filename;
                Storage::delete($old);
            }
            $user->avatar_filename = basename($file);
            $user->save();

            return redirect('/home')->with('success', '保存しました');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['file' => '画像がアップされていないか不正なデータです。']);
        }
    }
    */
}
