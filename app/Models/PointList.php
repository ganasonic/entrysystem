<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointList extends Model
{
    use HasFactory;
    protected $fillable = [
        'SAJNO',
        'FISNO',
        '氏名R',
        '氏名漢',
        '国名',
        '県連盟',
        'FIS_AEﾎﾟｲﾝﾄ',
        'FIS_HPﾎﾟｲﾝﾄ',
        'FIS_MOﾎﾟｲﾝﾄ',
        'FIS_SXﾎﾟｲﾝﾄ',
        'FIS_SSﾎﾟｲﾝﾄ',
        'FIS_BAﾎﾟｲﾝﾄ',
        'SAJ_AEﾎﾟｲﾝﾄ',
        'SAJ_HPﾎﾟｲﾝﾄ',
        'SAJ_MOﾎﾟｲﾝﾄ',
        'SAJ_SXﾎﾟｲﾝﾄ',
        'SAJ_SSﾎﾟｲﾝﾄ',
        'SAJ_BAﾎﾟｲﾝﾄ',
        '所属',
        '生年月日',
        'ｸﾗｽ',
        '氏名2',
        '学年',
        '競技者ｺｰﾄﾞ',
        '姓',
        '名',
        'ｾｲ',
        'ﾒｲ',
        'sei',
        'mei',
        '連盟ｺｰﾄﾞ',
        'ﾁｰﾑﾖﾐｶﾞﾅ',
        '性別',
    ];
    public function entrylist()
    {
        return $this->hasOne(Entrylist::class, 'SAJNO', 'SAJNO')
            ->where('user_id', auth()->id())
            ->where('competition_id', session('current_competition_id')); // コントローラ側でセットしておく
    }

}
