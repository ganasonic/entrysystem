<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrylist extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'competition_id',
        'SAJNO',
        'status',
        'delete_flg',
    ];

}
