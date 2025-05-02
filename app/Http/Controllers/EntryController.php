<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth0\Laravel\Facade\Auth0;

class EntryController extends Controller
{
    public function showEntryForm($tournamentId)
    {
        // $tournamentId を使用して、大会の情報を取得したり、ビューに渡したりする処理を記述します。
        return view('tournaments.entry', ['tournamentId' => $tournamentId]);
    }

}
