@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-4">
            <h2>大会詳細</h2>
            <div class="row">
                <div class="col-md-8">
                <h3 class="card-title">{{ $tournament->title ?? '大会名未定' }}</h3>
                <h4 class="card-title">{{ $tournament->fistitle ?? '' }}</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <p class="card-text"><strong>開催地:</strong> {{ $tournament->pref ?? '未登録' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>開催スキー場:</strong> {{ $tournament->place ?? '未登録' }}</p>
                </div>
                <div class="col-md-6">
                    <p class="card-text"><strong>コース名:</strong> {{ $tournament->course ?? '' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <p class="card-text"><strong>SAJ 男子CODEX:</strong> {{ $tournament->codex_sajm ?? '未登録' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>SAJ 女子CODEX:</strong> {{ $tournament->codex_sajf ?? '未登録' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>FIS 男子CODEX:</strong> {{ $tournament->codex_fism ?? '-' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>FIS 女子CODEX:</strong> {{ $tournament->codex_fisf ?? '-' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <p class="card-text"><strong>ランク:</strong> {{ $tournament->rank ?? '未登録' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>種目:</strong> {{ $tournament->discipline ?? '未登録' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>カテゴリ:</strong> {{ $tournament->category ?? '未登録' }}</p>
                </div>
                <div class="col-md-3">
                    <p class="card-text"><strong>開催県連:</strong> {{ $tournament->association ?? '未登録' }}</p>
                </div>
            </div>
            <p class="card-text"><strong>開催日:</strong> {{ $tournament->date ?? '' }} </p>
            <a href="{{ route('tournaments.select') }}" class="btn btn-secondary"><i class="fas fa-arrow-left mr-2"></i>大会選択へ戻る</a>
        </div>

        <div class="row">
            <div class="col-md-5">
                <h2>選手リスト</h2>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div>
                                <label class="mr-2">性別:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male">
                                    <label class="form-check-label mr-2" for="gender_male">男性</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female">
                                    <label class="form-check-label mr-2" for="gender_female">女性</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_all" value="" checked>
                                    <label class="form-check-label" for="gender_all">全て</label>
                                </div>
                            </div>
                            <div class="ml-auto">
                                <button type="button" class="btn btn-primary ml-2" id="search_players">検索</button>
                            </div>
                        </div>
                                <!--
                        <div class="form-group">
                            <label>性別:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male">
                                <label class="form-check-label" for="gender_male">男性</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female">
                                <label class="form-check-label" for="gender_female">女性</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="gender_all" value="" checked>
                                <label class="form-check-label" for="gender_all">全て</label>
                            </div>
                            <button type="button" class="btn btn-primary" id="search_players">検索</button>
                        </div>
-->
                        <div class="form-group">
                            <label for="prefecture">都道府県:</label>
                            <select class="form-control" id="prefecture">
                                <option value="">全て</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="team">チーム名:</label>
                            <select class="form-control" id="team">
                                <option value="">全て</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="player_name">選手名:</label>
                            <input type="text" class="form-control" id="player_name">
                        </div>
                    </div>
                </div>
                <ul class="list-group" id="player_list">
                    <li class="list-group-item">選手を検索してください</li>
                </ul>
            </div>

            <div class="col-md-2 d-flex flex-column justify-content-center align-items-center">
                <button type="button" class="btn btn-success mb-3" id="add_to_entry" disabled>選手をエントリー <i class="fas fa-arrow-right ml-2"></i></button>
                <button type="button" class="btn btn-danger mt-3" id="remove_from_entry" disabled><i class="fas fa-arrow-left mr-2"></i> エントリー解除</button>
            </div>

            <div class="col-md-5">
                <h2>エントリーリスト</h2>
                <ul class="list-group" id="entry_list">
                    <li class="list-group-item">エントリーされた選手はいません</li>
                </ul>
                <button type="button" class="btn btn-primary mt-3">エントリー確定</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script>
        $(document).ready(function() {
            const tournamentId = '{{ $tournament->id ?? '' }}'; // Blade テンプレートから大会IDを取得

            // 都道府県とチーム名の選択肢を非同期で取得し、コンボボックスに追加する処理 (APIエンドポイントは仮)
            $.getJSON('/api/prefectures', function(data) {
                $.each(data, function(key, val) {
                    $('#prefecture').append(`<option value="${val.id}">${val.name}</option>`);
                });
            });

            $.getJSON('/api/teams', function(data) {
                $.each(data, function(key, val) {
                    $('#team').append(`<option value="${val.id}">${val.name}</option>`);
                });
            });

            // 選手検索ボタンのイベントリスナー
            $('#search_players').click(function() {
                const gender = $('input[name="gender"]:checked').val();
                const prefecture = $('#prefecture').val();
                const team = $('#team').val();
                const playerName = $('#player_name').val();

                // 選手検索のAPIエンドポイントを呼び出す (APIエンドポイントは仮)
                $.getJSON(`/api/players?gender=${gender}&prefecture=${prefecture}&team=${team}&name=${playerName}`, function(data) {
                    $('#player_list').empty();
                    if (data.length > 0) {
                        $.each(data, function(key, player) {
                            $('#player_list').append(`<li class="list-group-item list-group-item-action" data-player-id="${player.id}">${player.name} (${player.team_name ?? '所属なし'})</li>`);
                        });
                    } else {
                        $('#player_list').append(`<li class="list-group-item">該当する選手は見つかりませんでした</li>`);
                    }
                    $('#add_to_entry').prop('disabled', true); // 検索後にエントリーボタンを無効化
                });
            });

            let selectedPlayerId = null;
            $('#player_list').on('click', '.list-group-item-action', function() {
                $('.list-group-item-action').removeClass('active');
                $(this).addClass('active');
                selectedPlayerId = $(this).data('player-id');
                $('#add_to_entry').prop('disabled', false);
            });

            let entryList = [];
            $('#add_to_entry').click(function() {
                if (selectedPlayerId) {
                    // 選手情報を取得するAPIエンドポイントを呼び出す (APIエンドポイントは仮)
                    $.getJSON(`/api/players/${selectedPlayerId}`, function(player) {
                        if (!entryList.some(entry => entry.id === player.id)) {
                            entryList.push(player);
                            updateEntryListDisplay();
                        }
                        selectedPlayerId = null;
                        $('#player_list .list-group-item-action.active').removeClass('active');
                        $('#add_to_entry').prop('disabled', true);
                    });
                }
            });

            $('#remove_from_entry').click(function() {
                const activeEntryItem = $('#entry_list .list-group-item-action.active');
                if (activeEntryItem.length > 0) {
                    const playerIdToRemove = activeEntryItem.data('player-id');
                    entryList = entryList.filter(player => player.id !== playerIdToRemove);
                    updateEntryListDisplay();
                    $('#remove_from_entry').prop('disabled', true);
                }
            });

            $('#entry_list').on('click', '.list-group-item-action', function() {
                $('.list-group-item-action').removeClass('active');
                $(this).addClass('active');
                $('#remove_from_entry').prop('disabled', false);
            });

            function updateEntryListDisplay() {
                $('#entry_list').empty();
                if (entryList.length > 0) {
                    $.each(entryList, function(key, player) {
                        $('#entry_list').append(`<li class="list-group-item list-group-item-action" data-player-id="${player.id}">${player.name} (${player.team_name ?? '所属なし'})</li>`);
                    });
                } else {
                    $('#entry_list').append(`<li class="list-group-item">エントリーされた選手はいません</li>`);
                }
            }

            // ページ読み込み時に大会情報を取得して表示 (Controllerから $tournament 変数を渡す前提)
            @if(isset($tournament))
                // 大会情報は Blade テンプレートで表示済み
            @else
                // 大会情報が渡ってきていない場合の処理 (例: エラーメッセージ表示など)
                $('#tournament-detail').html('<p class="text-danger">大会情報の取得に失敗しました。</p>');
            @endif
        });
    </script>
@endsection
