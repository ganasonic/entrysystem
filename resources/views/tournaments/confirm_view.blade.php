@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">エントリー済み確認</h2>

    <div class="card mb-4">
        <div class="card-header">大会情報</div>
        <div class="card-body">
            <p><strong>大会名：</strong>{{ $tournament->title }}</p>
            <p><strong>開催日：</strong>{{ $tournament->race_date }}</p>
            <p><strong>場所：</strong>{{ $tournament->place }}</p>
            <p><strong>カテゴリー：</strong>{{ $tournament->category }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">エントリー選手一覧</div>
        <div class="card-body table-responsive">
            @if(count($entries) > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SAJ番号</th>
                        <th>氏名</th>
                        <th>性別</th>
                        <th>県連盟</th>
                        <th>所属</th>
                        <th>ポイント</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($entries as $index => $player)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $player->SAJNO ?? '' }}</td>
                        <td>{{ $player->氏名漢 ?? '' }}</td>
                        <td>{{ $player->性別 ?? '' }}</td>
                        <td>{{ $player->県連盟 ?? '' }}</td>
                        <td>{{ $player->所属 ?? '' }}</td>
                        <td>{{ $player->SAJ_MOﾎﾟｲﾝﾄ ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p>エントリー情報はありません。</p>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">戻る</a>
    </div>
</div>
@endsection
