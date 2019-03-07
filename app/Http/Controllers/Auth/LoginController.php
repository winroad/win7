<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

//    protected function credentials(Request $request)
//    {
//        // オリジナルのコード
//        // return $request->only( $this->username(), 'password' );
//
//        return array_merge($request->only($this->username(), 'password'), ['activate' => '1']);
//    }
    /*
     * AuthenticateUsersのloginメソッドを上書き
     */
    public function login(Request $request)
    {
        // 入力のバリデーション
        $this->validateLogin($request);

        // ログインに何回も失敗するとログインできなくなる仕組み
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // 実際のログインのチェック
        if ($this->attemptLogin($request)) {
            // ログイン成功、以下はオリジナルコード
            // return $this->sendLoginResponse($request);

            // 以下のif文のロジックを追加

            // この時点でログイン済みユーザの情報はAuthで保持されているが、
            // リクエストインスタンスには存在しない
            if (auth()->user()->activate == false) {

                // アクティブではないので、ログアウトさせる
                // このメソッドの返却値は'/`へリダイレクトさせるので、無視する
                $this->logout($request);

                // 改めて、今回は直前のURI（ログインページ）へリダイレクト
                // withでフラッシュメッセージとしてエラーメッセージをセッションへ保存
                return redirect()->back()
                    ->withInput()
                    ->with('alert', 'アカウントが有効になっていません。サーバー管理者に確認して下さい');
            }

            return $this->sendLoginResponse($request);
        }

        // ログイン失敗、なので試行回数をインクリメント
        $this->incrementLoginAttempts($request);

        // ログイン失敗のレスポンス生成
        return $this->sendFailedLoginResponse($request);
    }
}
