<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Competition;
use App\Models\Entrylist;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $sajnoList = json_decode($request->input('players'), true);
        $tournamentId = $request->input('tournament_id');
        $tournament = Competition::where('id', $tournamentId)->first();
        //dd($request->input(),$sajnoList);
        $entryCount = count($sajnoList);
        if ($entryCount === 0) {
            return redirect()->back()->with('error', '選手が選択されていません');
        }
        //dd($entryCount,$tournamentId,$sajnoList);
        $amount = intval($tournament['entry_fee']) * $entryCount; // 1人あたり10000円と仮定（必要に応じて調整）
        //dd($tournament['entry_fee'], $amount);

        $players = DB::table('point_lists')->whereIn('SAJNO', $sajnoList)->get();

        //仮登録
        $user = Auth::user(); // Auth0経由のユーザー情報取得
        $auth0Email = $user->email;
        $user = User::where('email', $auth0Email)->first();
        //dd($auth0Email, $user->id);

        foreach ($sajnoList as $sajno) {
            //Entrylist::create([
            //    'user_id' => $user->id,
            //    'competition_id' => $tournamentId,
            //    'SAJNO' => $sajno,
            //    'status' => 'pending',
            //    'delete_flg' => false,
            //]);
            Entrylist::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'competition_id' => $tournamentId,
                    'SAJNO' => $sajno,
                ],
                [
                    'status' => 'pending',
                    'delete_flg' => false,
                ]
            );

        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => "大会エントリー（$entryCount 名）",
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            /*'success_url' => route('entry.complete') . '?success=1&sajnoList=' . $sajnoList,*/
            'success_url' => route('entry.complete.redirect') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('tournaments.select') . '?cancel=1',
            'metadata' => [
                'tournament_id' => $tournamentId,
                'player_count' => $entryCount,
                'sajnoList' => implode(',', $sajnoList),
            ]
        ]);

        return redirect($session->url);
    }

//    public function redirectToComplete(Request $request)
//    {
//        Stripe::setApiKey(env('STRIPE_SECRET'));
//
//        $sessionId = $request->query('session_id');
//
//        // セッションを取得
//        $session = Session::retrieve($sessionId);
//
//        // 必要な情報を取り出す
//        $tournamentId = $session->metadata->tournament_id ?? null;
//        $playerCount = $session->metadata->player_count ?? null;
//        $sajnoList = $session->metadata->sajnoList ?? null;
//
//        return view('entry.complete-redirect', [
//            'tournamentId' => $tournamentId,
//            'playerCount' => $playerCount,
//            'sajnoList' => $sajnoList,
//            'sessionId' => $sessionId
//        ]);
//    }

    public function success()
    {
        return '決済成功';
    }

    public function cancel()
    {
        return '決済キャンセル';
    }
}
