@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>大会選択</h1>
        <div class="row">
            <div class="col-md-6" style="max-height: 75vh; overflow-y: auto;">

            <ul class="list-group">
                @forelse ($tournaments as $tournament)
                    <button type="button" class="list-group-item list-group-item-action" data-tournament-id="{{ $tournament->id ?? $loop->index + 1 }}">
                        {{ $tournament->title ?? '大会名未定' }}
                    </button>
                @empty
                    <li class="list-group-item">大会情報はありません。</li>
                @endforelse
            </ul>

            </div>
            <div class="col-md-6" id="tournament-detail">
                <h2>大会詳細</h2>
                <p>大会リストから選択すると、詳細が表示されます。</p>
                <a href="#" id="entry-button" class="btn btn-primary" style="position: absolute; bottom: 10px; right: 10px; display: none;">エントリー</a>
            </div>
        </div>
        <a href="{{ url('/dashboard') }}" class="btn btn-secondary back-button"><i class="fas fa-arrow-left mr-2"></i>戻る</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.list-group-item-action').click(function() {
                var tournamentId = $(this).data('tournament-id');
                $('.list-group-item-action').removeClass('active');
                $(this).addClass('active');

                $.ajax({
                    url: '/tournaments/' + tournamentId, // 大会詳細を取得するAPIエンドポイント
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#tournament-detail').html(`
                            <h3>${data.title}</h3>
                            <p><strong>ランク:</strong> ${data.rank ?? ''}</p>
                            <p><strong>英語タイトル:</strong> ${data.fistitle ?? ''}</p>
                            <p><strong>シーズン:</strong> ${data.season}</p>
                            <p><strong>開催地:</strong> ${data.pref ?? ''}</p>
                            <p><strong>開催スキー場:</strong> ${data.place ?? ''}</p>
                            <p><strong>コース名:</strong> ${data.course ?? ''}</p>
                            <p><strong>開催県連:</strong> ${data.association ?? ''}</p>
                            <p><strong>開催日:</strong> ${data.race_date}</p>
                            <p><strong>カテゴリ:</strong> ${data.category ?? ''}</p>
                            <p><strong>種目:</strong> ${data.discipline?? ''}</p>
                            <p><strong>SAJ 男子コード:</strong> ${data.codex_sajm ?? '未登録'}</p>
                            <p><strong>SAJ 女子コード:</strong> ${data.codex_sajf ?? '未登録'}</p>
                            <p><strong>FIS 男子コード:</strong> ${data.codex_fism ?? '未登録'}</p>
                            <p><strong>FIS 女子コード:</strong> ${data.codex_fisf ?? '未登録'}</p>
                            <p><strong>エントリー費:</strong> ${data.entry_fee ?? '未登録'}</p>
                            <p><strong>最低ポイント:</strong> ${data.minimum_point ?? '未登録'}</p>
                            <a href="/tournaments/${data.id}/entry" class="btn btn-primary" style="position: absolute; bottom: 10px; right: 10px;">エントリー</a>
                        `);
                    },
                    error: function(error) {
                        $('#tournament-detail').html('<p class="text-danger">大会情報の取得に失敗しました。</p>');
                        $('#entry-button').hide(); // エラー時はエントリーボタンを非表示
                        console.error(error);
                    }
                });
            });
        });
    </script>

@endsection
