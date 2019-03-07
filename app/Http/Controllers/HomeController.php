<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\WinImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use Image;
use App\Models\PhotoStore;

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
    /*
     * 新規登録直後のページ
     */
    public function newly()
    {

        return view('newly');
    }
    public function index($num=20)
    {
        $user = User::find(auth()->id());
        $win = new WinImage();
        $thumbs = $win->getMyThumbs($user,$num);
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
    public function photoTurn($id,$rotate)
    {
        if($rotate == 1){
            $angle = 270;
        }elseif($rotate == 2){
            $angle = 90;
        }elseif($rotate == 3){
            $angle = 180;
        }else{
            return back();
        }
        $store = PhotoStore::find($id);
        $win = new WinImage();
        $win->turn($store->id,$angle);
        return redirect('home');
    }
}
