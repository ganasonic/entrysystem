@php
    use Carbon\Carbon;
@endphp
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>エントリー確認</h1>

    <form method="GET" action="">
        <div class="mb-3">
            <label for="competition_id" class="form-label">大会を選択してください:</label>
            <select name="competition_id" id="competition_id" class="form-select" >
                <option value="">-- 大会を選択 --</option>
                @foreach($competitions as $competition)
                    <option value="{{ $competition->id }}" {{ request('competition_id') == $competition->id ? 'selected' : '' }}>
                        {{ $competition->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    @if(!empty($entries))
        <h2>エントリーリスト</h2>
        <table id="entry_table" class="table table-bordered">
            <thead>
                <tr>
                    <th>SAJNO</th>
                    <th>名前</th>
                    <th>性別</th>
                    <th>所属</th>
                    <th>ポイント</th>
                    <th>年齢</th>
                    <th>ステータス</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="stripe-button-container" style="display: none; margin-top: 20px;">
            <form action="{{ route('checkout') }}" method="POST" id="entryForm">
                @csrf
                <input type="hidden" name="tournament_id" id="tournament_id">
                <input type="hidden" name="players" id="playersInput">
                <button type="submit" class="btn btn-primary">支払いへ進む</button>
            </form>
        </div>

    @endif
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    let competitionId;
    function calculateAge(birthDateString) {
        if (!birthDateString) return '';
        const today = new Date();
        const birthDate = new Date(birthDateString);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }
    $('#entryForm').on('submit', function () {
        const sajnoList = [];
        // テーブルの各行から SAJNO を取得
        $('#entry_table tbody tr').each(function () {
            const sajno = $(this).find('td').eq(0).text().trim();
            if (sajno) {
                sajnoList.push(sajno);
            }
        });
        $('#playersInput').val(JSON.stringify(sajnoList));
        $('#tournament_id').val(competitionId);
    });

    $('#competition_id').on('change', function () {
        competitionId = $(this).val();
        const $tbody = $('#entry_table tbody');
        $tbody.empty();

        if (!competitionId) return;

        $url = "/entry/fetch/" + competitionId;
        $('#tournament_id').val(competitionId);

        $.ajax({
            url: $url,//`/entry/fetch/${competitionId}`,
            method: 'GET',
            success: function (data) {
                let hasPending = false;
                data.forEach(entry => {
                    const age = calculateAge(entry.生年月日);
                    if (entry.status === 'pending') {
                        hasPending = true;
                    }
                    const row = `
                        <tr>
                            <td>${entry.SAJNO}</td>
                            <td>${entry.姓} ${entry.名}</td>
                            <td>${entry.性別}</td>
                            <td>${entry.所属}</td>
                            <td style="text-align: right">${entry.SAJ_MOﾎﾟｲﾝﾄ !== null ? Number(entry.SAJ_MOﾎﾟｲﾝﾄ).toFixed(2) : ''}</td>
                            <td style="text-align: right">${age} 歳</td>
                            <td>${entry.status}</td>
                        </tr>`;
                    $tbody.append(row);
                });
                if (hasPending) {
                    $('#stripe-button-container').show();
                } else {
                    $('#stripe-button-container').hide();
                }

            },
            error: function () {
                alert('エントリーデータの取得に失敗しました。');
            }
        });
    });
});
</script>
@endsection
