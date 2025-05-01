<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth0\Laravel\Facade\Auth0;

class Auth0Controller extends Controller
{
    public function callback(Request $request)
    {
        // Auth0からユーザー情報を取得
        $user = Auth0::getUser(); // 修正部分

        $user = auth()->user();//Auth0::user();
        $user = Auth0::user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        // ユーザー情報をセッションに保存してログイン
        auth()->loginUsingId($user['sub']); // sub を使ってユーザーをログイン

        return redirect('/');
    }

    public function logout()
    {
        Auth0::logout();
        return redirect('/');
    }
}
