<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mail\ActivateMail;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/newly';
    protected $adminMail = 'nakada@builwing.com';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    public function pre_check(Request $request){
        $this->validator($request->all())->validate();
        //flash data
        $request->flashOnly( 'email');

        $bridge_request = $request->all();
        // password マスキング
        $bridge_request['password_mask'] = '******';

        return view('auth.register_check')->with($bridge_request);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verify_token' => base64_encode($data['email']),
        ]);
        \Mail::to($user->email)
            ->send(new ActivateMail($user)); // 引数にリクエストデータを渡す
        return $user;
    }
    /*
     * ユーザー登録
     */
    public function register(Request $request)
    {
        event(new Registered($user = $this->create( $request->all() )));

        return view('auth.registered');
    }
    /*
     * ユーザーのアクティベート
     */
    public function activate($token)
    {
        //本登録の制限時間
        $limit = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . '+3 hour'));
        //本登録申請ユーザー
        $user = User::where('email_verify_token',$token)->first();
        if($user && $user->created_at < $limit){
            $user->activate = 1;
            $user->save();
            return redirect('login')->with('alert','本登録が完了しましたので、ログインして下さい。');
        }
//        dd($user->name);
    }
}
