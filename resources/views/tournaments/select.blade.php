<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>大会選択 - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        .list-group-item.active {
            background-color: #007bff;
            border-color: #007bff;
        }
        #tournament-detail {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #tournament-detail h2 {
            margin-bottom: 15px;
            color: #343a40;
        }
        #tournament-detail p {
            margin-bottom: 10px;
        }
        .back-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>大会選択</h1>
        <div class="row">
            <div class="col-md-6" style="max-height: 75vh; overflow-y: auto;">
                <ul class="list-group">
                    @if (is_array($tournaments) && !empty($tournaments))
                        @foreach ($tournaments as $tournament)
                            <button type="button" class="list-group-item list-group-item-action" data-tournament-id="{{ $tournament->id ?? $loop->index + 1 }}">
                                {{ $tournament->title ?? '大会名未定' }}
                            </button>
                        @endforeach
                    @else
                        <li class="list-group-item">大会情報はありません。</li>
                    @endif
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
</body>
</html>
