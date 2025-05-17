@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{ isset($profile) ? 'プロフィール変更' : 'エントリー担当者情報登録' }}</h2>
    <form method="POST" action="{{ isset($profile) ? route('profile.update') : route('user.register') }}">
        @csrf

        <input type="hidden" name="email" value="{{ $profile->email ?? $email ?? '' }}">

        <div class="mb-3">
            <label>姓</label>
            <input type="text" name="family_name" class="form-control" required
                value="{{ old('family_name', $profile->family_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label>名</label>
            <input type="text" name="given_name" class="form-control" required
                value="{{ old('given_name', $profile->given_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label>ミドルネーム</label>
            <input type="text" name="middle_name" class="form-control"
                value="{{ old('middle_name', $profile->middle_name ?? '') }}">
        </div>
        <div class="mb-3">
            <label>都道府県</label>
            <input type="text" name="prefecture" class="form-control"
                value="{{ old('prefecture', $profile->prefecture ?? '') }}">
        </div>
        <div class="mb-3">
            <label>所属</label>
            <input type="text" name="team" class="form-control"
                value="{{ old('team', $profile->team ?? '') }}">
        </div>
        <div class="mb-3">
            <label>電話番号</label>
            <input type="text" name="tel" class="form-control" required
                value="{{ old('tel', $profile->tel ?? '') }}">
        </div>
        <button type="submit" class="btn btn-primary">
            {{ isset($profile) ? 'プロフィールを更新' : '登録してログイン' }}
        </button>
    </form>
</div>
@endsection
