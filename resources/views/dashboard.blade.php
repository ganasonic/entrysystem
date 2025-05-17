<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>会員ページ - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e9ecef;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand {
            color: #fff;
        }
        .navbar-nav .nav-link {
            color: #fff;
        }
        .container {
            margin-top: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ url('/dashboard') }}">Competition Entry System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <span class="nav-link">{{ Auth::user()->name ?? 'ゲスト' }}</span>
                </li>
                <li class="nav-item">
                    @auth
                    <a class="nav-link" href="{{ route('logout') }}">ログアウト</a>
                    @else
                    <a class="nav-link" href="{{ route('login') }}">ログイン</a>
                    @endauth
                </li>
            </ul>
        </div>
    </nav>

    @auth
    <div class="container">
        @if (false)
        <h1>ようこそ、{{ Auth::user()->name ?? 'ゲスト' }} さん！</h1>
        @else
        <h2>ログイン中： {{ $loginname ?? 'ゲスト' }} さん</h2>
        @endif
        <p class="lead">FreeStyle Ski Competition Entry Systemの会員専用のページです。</p>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
        </div>
    @endif

    </div>
    @include('main')
    @else
    <div class="container">
        <h1>ログインしていません。</h1>
    </div>
    @endauth

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
