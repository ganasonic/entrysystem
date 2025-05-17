@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')

@section('content')
<div class="container"> <h2 class="mb-4">エントリー確認</h2>

<div class="mb-3">
    <h4>大会名：{{ $tournament->title ?? '大会名未定' }}</h4>
</div>

@if (count($entries) > 0)
<form action="{{ route('checkout') }}" method="POST">
    @csrf
    <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
    <input type="hidden" name="players" value="{{ json_encode($entries) }}">

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>SAJNO</th>
                    <th>選手名</th>
                    <th>性別</th>
                    <th>県連盟</th>
                    <th>所属</th>
                    <th>ポイント</th>
                    <th>年齢</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $index => $player)
                    <tr data-sajno="{{ $player->SAJNO }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $player->SAJNO ?? '' }}</td>
                        <td>{{ $player->氏名漢 ?? '' }}</td>
                        <td>{{ $player->性別 ?? '' }}</td>
                        <td>{{ $player->県連盟 ?? '' }}</td>
                        <td>{{ $player->所属 ?? '' }}</td>
                        <td style="text-align: right">{{ number_format(floatval($player->SAJ_MOﾎﾟｲﾝﾄ ?? 0), 2) }}</td>
                        <td style="text-align: right">
                            @if (!empty($player->生年月日))
                                {{ Carbon::parse($player->生年月日)->age }}歳
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('entry.remove', $player->SAJNO) }}" method="POST" onsubmit="return confirm('本当に削除しますか？')">
                                @csrf
                                <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                                <button class="btn btn-sm btn-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-between">
        <a href="{{ route('tournament.entry.form', $tournament->id) }}" class="btn btn-secondary">戻る</a>
        <form action="{{ route('checkout') }}" method="POST" id="entryForm">
            @csrf
            <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
            <input type="hidden" name="players" id="playersInput">
            <button type="submit" class="btn btn-primary">支払いへ進む</button>
        </form>
    </div>
</form>
@else
    <div class="alert alert-info">エントリーされた選手がいません。</div>
    <a href="{{ route('tournament.entry.form', $tournament->id) }}" class="btn btn-secondary">選手を追加する</a>
@endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script>
        $(document).ready(function() {
        });

        $('#entryForm').on('submit', function () {
            const sajnoList = [];
            $('tbody tr[data-sajno]').each(function () {
                const sajno = $(this).data('sajno');
                if (sajno) {
                    sajnoList.push(sajno);
                }
            });

            $('#playersInput').val(JSON.stringify(sajnoList));
        });

        $('#remove_from_entry').click(function() {
            const activeEntryItem = $('#entry_list .list-group-item-action.active');
            if (activeEntryItem.length > 0) {
                const playerIdToRemove = activeEntryItem.data('player-id');
                const removedPlayer = entryList.find(player => player.id === playerIdToRemove);
                entryList = entryList.filter(player => player.id !== playerIdToRemove);
                updateEntryListDisplay();
                $('#remove_from_entry').prop('disabled', entryList.length === 0);
                // 左側のリストに削除した選手を再追加 (必要であれば)
                if (removedPlayer) {
                    $('#player_list').append(`<li class="list-group-item list-group-item-action" data-player-id="${removedPlayer.id}" data-player-name="${removedPlayer.name}" data-player-team="${removedPlayer.team_name}">${removedPlayer.name} (${removedPlayer.team_name ?? '所属なし'})</li>`);
                }
            }
        });

    </script>

@endsection
