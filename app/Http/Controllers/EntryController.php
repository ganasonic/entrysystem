<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Auth0\Laravel\Facade\Auth0;
use Illuminate\Support\Facades\DB;
use App\Models\Competition;
use App\Models\Entrylist;
use App\Models\PointList;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Carbon\Carbon;

class EntryController extends Controller
{
    public function showEntryForm($tournamentId)
    {
        if (! auth()->check()) {
            return redirect()->route('login'); // 未ログイン時はログイン誘導画面へリダイレクト
        }
        $prefectures = DB::table('point_lists')
            ->distinct()
            ->pluck("県連盟")
            ->toArray(); // Blade で扱いやすいように配列に変換
        $teams = DB::table('point_lists')
        ->distinct()
        ->pluck("所属")
        ->toArray(); // Blade で扱いやすいように配列に変換

        $tournament = Competition::where('id', $tournamentId)->first();

            // $tournamentId を使用して、大会の情報を取得したり、ビューに渡したりする処理を記述します。
        return view('tournaments.entry', compact(['tournament', 'prefectures','teams']));
    }

    public function search(Request $request)
    {
        $currentDate = Carbon::now();
        //今年の12月31日を起点にする
        $endOfYear = $currentDate->month >= 5
            ? Carbon::create($currentDate->year, 12, 31, 23, 59, 59)
            : Carbon::create($currentDate->year - 1, 12, 31, 23, 59, 59);


        $gender = $request->input('gender');
        $prefecture = $request->input('prefecture');
        $team = $request->input('team');
        $playerName = $request->input('player_name');
        $tournamentId = $request->input('tournament_id');

        $category = Competition::where('id', $tournamentId)->value('category');
        $minimum_point = Competition::where('id', $tournamentId)->value('minimum_point');
        //dd($category );
        // 女子の最低ランクを取得
        $minimumPointFemale = config('ranks.minimum_point_f');
        // 男子の最低ランクを取得
        $minimumPointMale = config('ranks.minimum_point_m');
        //Jrの年齢12歳以下
        $junior_age = config('ranks.junior_age');
        //NJCの制限年齢19歳以下
        $njc_age = config('ranks.njc_age');

        //dd($request->input());
        $query = DB::table('point_lists'); // players テーブルを仮定

        if (!empty($gender)) {
            $query = $query->where('性別', $gender);
        }
        if (!empty($prefecture)) {
            $query = $query->where('県連盟', $prefecture);
        }
        if (!empty($team)) {
            $query = $query->where('所属', $team);
        }
        if (!empty($playerName)) {
            $query = $query->where('氏名漢', 'like', '%' . $playerName . '%');
        }

        $consider = $request->input('qualification');
        //資格考慮の場合
        if ($consider == 1) {
            //A級の場合
            if(strpos($category, 'A') !== false){
                if (!empty($gender)) {
                    if ($gender === '女') {
                        $query = $query->where('ランク', '<=', $minimumPointFemale);
                    } elseif ($gender === '男') {
                        $query = $query->where('ランク', '<=', $minimumPointMale);
                    }
                } else {
                    $query = $query->where(function ($subQuery) {
                        $subQuery->where(function ($q) {
                            $q->where('性別', '女')->where('ランク', '<=', 50);
                        })->orWhere(function ($q) {
                            $q->where('性別', '男')->where('ランク', '<=', 80);
                        });
                    });
                }
            }
            //Jrの場合
            elseif(strpos($category, 'Jr') !== false){
                $cutoffDate = $endOfYear->copy()->subYears($junior_age)->addDay(); // 年末から12年前の翌日
                $query = $query->where('生年月日', '>=', $cutoffDate);
                //dd("Jrの場合",$junior_age, $cutoffDate);
            }
            //NJCの場合
            elseif(strpos($category, 'NJC') !== false){
                //dd("NJCの場合");
                $cutoffDate = $endOfYear->copy()->subYears($njc_age)->addDay(); // 年末から19年前の翌日
                $query = $query->where('生年月日', '>=', $cutoffDate->startOfDay());
                //dd($minimum_point);
                $query = $query->where('SAJ_MOﾎﾟｲﾝﾄ', '>=', intval($minimum_point) );
            }
            //NCの場合
            if(strpos($category, 'NC') !== false){}
            //B級の場合
            if(strpos($category, 'B') !== false){}

        }
        $players = $query->get();
        //dd($consider, $category, $players);
        //dd($players);
        // JSON 形式でデータを返す
        return response()->json($players);
    }

    public function confirm0(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login'); // 未ログイン時はログイン誘導画面へリダイレクト
        }
        $prefectures = DB::table('point_lists')
            ->distinct()
            ->pluck("県連盟")
            ->toArray(); // Blade で扱いやすいように配列に変換
        $teams = DB::table('point_lists')
        ->distinct()
        ->pluck("所属")
        ->toArray(); // Blade で扱いやすいように配列に変換

            // $tournamentId を使用して、大会の情報を取得したり、ビューに渡したりする処理を記述します。
        return view('tournaments.entry_confirm', compact(['tournamentId', 'prefectures','teams']));
    }

    public function confirm(Request $request)
    {
        if (! auth()->check()) {
            return redirect()->route('login'); // 未ログイン時はログイン誘導画面へリダイレクト
        }
        // バリデーション（任意）
        $request->validate([
            'players' => 'required|string',
        ]);

        // JSON 文字列 → 配列へ変換
        $sajnoList = json_decode($request->input('players'), true);
        // SAJNOリストから選手情報を取得（例：DBから）
        $players = DB::table('point_lists')->whereIn('SAJNO', $sajnoList)->get();
        $tournamentId = $request->input('tournament_id');
        //dd($request->input(), $players);
        $tournament = Competition::where('id', $tournamentId)->first();

        // 選手情報をビューに渡す
        return view('tournaments.entry_confirm', [
            'entries' => $players,
            'tournament' => $tournament
        ]);
    }

    public function remove(){

    }

    public function redirectToComplete(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->query('session_id');

        // セッションを取得
        $session = Session::retrieve($sessionId);

        // 必要な情報を取り出す
        $tournamentId = $session->metadata->tournament_id ?? null;
        $playerCount = $session->metadata->player_count ?? null;
        $sajnoList = $session->metadata->sajnoList ?? null;

        return view('tournaments.complete-redirect', [
            'tournamentId' => $tournamentId,
            'playerCount' => $playerCount,
            'sajnoList' => $sajnoList,
            'sessionId' => $sessionId
        ]);
    }

    public function complete(Request $request){
        $tournamentId = $request->input('tournament_id');
        $playerCount = $request->input('player_count');
        $sessionId = $request->input('session_id');

        $sajnoList = $request->input('sajnoList');
        $sajnoArray = explode(',', $sajnoList);
        //dd($sajnoList,$sajnoArray);

        //dd($sajnoList,$request->input());
        // SAJNOリストから選手情報を取得（例：DBから）
        $players = DB::table('point_lists')->whereIn('SAJNO', $sajnoArray)->get();

        //本登録
        $user = Auth::user(); // Auth0経由のユーザー情報取得
        $auth0Email = $user->email;
        $user = User::where('email', $auth0Email)->first();
        //dd($auth0Email, $user->id);

        foreach ($sajnoArray as $sajno) {
            Entrylist::where('user_id', $user->id)
                ->where('competition_id', $tournamentId)
                ->where('SAJNO', $sajno)
                ->update(['status' => 'complete']);
        }



        $tournament = Competition::where('id', $tournamentId)->first();
        return view('tournaments.confirm_view', [
            'entries' => $players,
            'tournament' => $tournament
        ]);
    }

    public function list(){
        if (! auth()->check()) {
            return redirect()->route('login'); // 未ログイン時はログイン誘導画面へリダイレクト
        }
        $user = Auth::user(); // Auth0経由のユーザー情報取得
        $auth0Email = $user->email;
        $user = User::where('email', $auth0Email)->first();

        // 1. Entrylist から現在ログイン中のユーザーのエントリー情報を取得
        $entrylists = Entrylist::where('user_id', $user->id)->get();

        // 2. 大会IDリストを抽出し、重複を排除
        $competitionIds = $entrylists->pluck('competition_id')->unique();

        // 3. 大会情報を取得
        $competitions = Competition::whereIn('id', $competitionIds)->get();

        $entries = collect(); // 初期化（空のコレクション）

        return view('tournaments.list',compact(['entries', 'competitions']));
    }

    public function fetchEntries($competition_id)
    {
        $user = Auth::user();
        $auth0Email = $user->email;
        $user = User::where('email', $auth0Email)->first();
//dd($user);
        $sajnos = Entrylist::where('user_id', $user->id)
            ->where('competition_id', $competition_id)
            ->pluck('SAJNO');

        //$entries = PointList::whereIn('SAJNO', $sajnos)
        //    ->select('SAJNO', '姓', '名', '性別', '所属')
        //    ->get();

        $entries = DB::table('point_lists')
            ->join('entrylists', function ($join) use ($user, $competition_id) {
                $join->on('point_lists.SAJNO', '=', 'entrylists.SAJNO')
                    ->where('entrylists.user_id', $user->id)
                    ->where('entrylists.competition_id', $competition_id);
            })
            ->select('point_lists.SAJNO', 'point_lists.姓', 'point_lists.名', 'point_lists.性別', 'point_lists.所属', 'point_lists.SAJ_MOﾎﾟｲﾝﾄ', 'point_lists.生年月日', 'entrylists.status')
            ->get();

        return response()->json($entries);
    }
}
