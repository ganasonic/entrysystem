<?php

namespace App\Http\Controllers;

use Auth0\Laravel\Facade\Auth0;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;


class Auth0Controller extends Controller
{
    public function showLoginPrompt(): View
    {
        return view('auth.login-prompt');
    }

    public function showLoggedOut(): View
    {
        return view('auth.logged-out');
    }

    public function showDashboard(): View
    {
        return view('dashboard');
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
        Auth::logout();
        return redirect()->route('logout');
    }

}
