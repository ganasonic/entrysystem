<?php

namespace App\Http\Controllers;

use Auth0\Laravel\Facade\Auth0;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse; // これを使用する
use App\Models\Competition;
use App\Models\User;
use Illuminate\Http\Request;

class Auth0Controller extends Controller
{
    // モックの大会データ
    private $tournaments;

    public function __construct()
    {
    }

    public function showLoginPrompt(): View
    {
        return view('auth.login-prompt');
    }

    public function showLoggedOut(): View
    {
        return view('auth.logged-out');
    }

    public function showDashboard(): View|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('login'); // 未ログイン時はログイン誘導画面へリダイレクト
        }
        $user = Auth::user(); // Auth0経由のユーザー情報取得
        //dd($user,auth()->check());
        $auth0Email = $user->email;
        $localUser = User::where('email', $auth0Email)->first();

        if (!$localUser) {
            // セッションにAuth0のユーザーデータを保持（後で使う）
            session(['auth0_user' => [
                'email' => $auth0Email
            ]]);

            // 新規登録画面へ
            return redirect()->route('user.register.form');
        }

        $result = User::where('email', $auth0Email)->first();
        $loginname = $result->family_name . '' . $result->given_name;
        //dd($loginname);
        return view('dashboard', compact(['loginname']));
    }

    public function profilechange()
    {
        $user = Auth::user(); // Auth0経由のユーザー情報取得
        //dd($user,auth()->check());
        $auth0Email = $user->email;
        $profile = User::where('email', $auth0Email)->first();
        return view('auth.register', compact(['profile']));
    }

    public function update(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string|max:255',
            'given_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'prefecture' => 'nullable|string|max:255',
            'team' => 'nullable|string|max:255',
            'tel' => 'required|string|max:20',
        ]);
        $user = Auth::user(); // Auth0経由のユーザー情報取得
        //dd($user,auth()->check());
        $auth0Email = $user->email;
        $profile = User::where('email', $auth0Email)->first();

        // プロフィール情報を更新
        $profile->update([
            'family_name' => $request->input('family_name'),
            'given_name' => $request->input('given_name'),
            'middle_name' => $request->input('middle_name'),
            'prefecture' => $request->input('prefecture'),
            'team' => $request->input('team'),
            'tel' => $request->input('tel'),
        ]);

        // 完了メッセージと共にダッシュボードなどにリダイレクト
        return redirect()->route('dashboard')->with('success', 'プロフィールを更新しました');
    }

    public function signup(): Response|RedirectResponse
    {
        //dd(env('AUTH0_CLIENT_ID'), config('auth0.routes.callback'), env('AUTH0_AUDIENCE'));
        $query = http_build_query([
            'client_id' => env('AUTH0_CLIENT_ID'),
            'redirect_uri' => env('AUTH0_REDIRECT_URI'),// url('/callback'), // 明示的に指定config('auth0.routes.callback'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'screen_hint' => 'signup',
            'audience' => env('AUTH0_AUDIENCE'),
        ]);
//dd(env('AUTH0_CLIENT_ID'), config('auth0.routes.callback'), env('AUTH0_AUDIENCE'),$query);
        return redirect('https://' . env('AUTH0_DOMAIN') . '/authorize?' . $query);
    }

    public function passwordchange()
    {
        //https://{{ env('AUTH0_DOMAIN') }}/u/login-settings?returnTo={{ urlencode(route('dashboard')) }}&client_id={{ env('AUTH0_CLIENT_ID') }}
        $query = http_build_query([
            'returnTo' => urlencode(route('dashboard')),
            'client_id' => env('AUTH0_CLIENT_ID'),
        ]);
        return redirect('https://' . env('AUTH0_DOMAIN') . '/u/login-settings?' . $query);
    }

    public function private(): Response
    {
        return response('Welcome! You are logged in.');
    }

    public function scope(): Response
    {
        return response('You have `read:messages` permission, and can therefore access this resource.');
    }

    public function index(): Response|RedirectResponse
    {
        if (! auth()->check()) {
            return redirect()->route('login'); // 未ログイン時はログイン誘導画面へリダイレクト
        }

        $user = auth()->user();
        $name = $user->name ?? 'User';
        $email = $user->email ?? '';

        return response("Hello {$name}! Your email address is {$email}.");
    }

    public function colors(): Response
    {
        $endpoint = Auth0::management()->users();

        $colors = ['red', 'blue', 'green', 'black', 'white', 'yellow', 'purple', 'orange', 'pink', 'brown'];

        $endpoint->update(
            id: auth()->id(),
            body: [
                'user_metadata' => [
                    'color' => $colors[random_int(0, count($colors) - 1)]
                ]
            ]
        );

        $metadata = $endpoint->get(auth()->id()); // Retrieve the user's metadata.
        $metadata = Auth0::json($metadata); // Convert the JSON to a PHP array.

        $color = $metadata['user_metadata']['color'] ?? 'unknown';
        $name = auth()->user()->name;

        return response("Hello {$name}! Your favorite color is {$color}.");
    }

    public function logout(): RedirectResponse
    {
        Auth::logout(); // Laravel セッションを削除
        $returnTo = urlencode('https://entry.saj-fs.com');
        $clientId = env('AUTH0_CLIENT_ID');     // ← env() で読み込まれるようにする
        $domain = env('AUTH0_DOMAIN');

        if (empty($clientId) || empty($domain)) {
            abort(500, 'Auth0 client ID or domain is not set.');
        }
        $logoutUrl = "https://{$domain}/v2/logout?client_id={$clientId}&returnTo={$returnTo}";
        return redirect()->away($logoutUrl);
    }

    public function showTournamentSelect(): View
    {
        $tournaments = Competition::all();
        return view('tournaments.select', compact('tournaments'));
    }

    public function getTournamentDetails(int $id): JsonResponse
    {
        $tournament = Competition::where('id', $id)->first();

        if ($tournament) {
            return response()->json($tournament);
        } else {
            return response()->json(['message' => '大会情報が見つかりません'], 404);
        }

    }

    public function showRegistrationForm()
    {
        $auth0User = session('auth0_user');
        if (!$auth0User || !isset($auth0User['email'])) {
            return redirect()->route('login')->with('error', '無効なセッションです。');
        }

        return view('auth.register', ['email' => $auth0User['email']]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string|max:255',
            'given_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'prefecture'  => 'nullable|string',
            'team'        => 'nullable|string',
            'tel'         => 'required|string|unique:users,tel',
        ]);
        //dd($request->input());
        $email = session('auth0_user.email');
        $user = User::create([
            'family_name' => $request->input('family_name'),
            'given_name'  => $request->input('given_name'),
            'middle_name' => $request->input('middle_name'),
            'prefecture'  => $request->input('prefecture'),
            'team'        => $request->input('team'),
            'tel'         => $request->input('tel'),
            'email'       => $email,
        ]);
        //Auth::login($user); // ログインさせる
        session()->forget('auth0_user'); // セッション消す

        return redirect()->route('dashboard');
    }

}
