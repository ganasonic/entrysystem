<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    protected $fillable = [
        'id', 'rank', 'title', 'fistitle', 'season', 'pref',
        'place', 'course', 'association', 'race_date',
        'category', 'discipline', 'sex',
        'codex_sajf', 'codex_sajm', 'codex_fisf', 'codex_fism',
        'entry_fee', 'minimum_point',
    ];

    public $incrementing = false; // IDを手動で設定するため
}
