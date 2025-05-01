<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ログアウトしました - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .logout-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #545b62;
            border-color: #4e555b;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <h1>ログアウトしました</h1>
        <p class="lead">ご利用ありがとうございました。</p>
        <a href="{{ url('/') }}" class="btn btn-secondary">トップページへ</a>
        <a href="{{ url('/login') }}" class="btn btn-primary ml-2">再度ログイン</a>
    </div>
</body>
</html>
