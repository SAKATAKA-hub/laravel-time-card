<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ログインフォーム</title>

    <!-- favicon -->
    <link rel="icon" href="{{asset('svg/logo.svg')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/signin.css') }}" rel="stylesheet">
</head>


<body>

    <main class="form-signin">

        <!-- rogo -->
        <h1  class="mb-5 text-center">
            <img src="{{asset('svg/logo.svg')}}" alt="" width="30" height="30">
            <div class="text-primary fw-bold ms-1">Time Card</div>
        </h1>


        <div class="card">
            <div class="card-body">
                <form method="post" action="{{route('login')}}">
                    @csrf
                    <h2 class="h5 fw-bold mb-3 text-center">ログイン</h2>

                    <!-- error -->
                    @if (session('login_error'))
                        <div class="text-danger mb-3">※{{ session('login_error') }}</div>
                    @endif


                    <label for="inputEmail" class="text-start">メールアドレス</label>
                    <input type="email" name="email" id="inputEmail" class="form-control mb-4" placeholder="Email address" required autofocus>
                    <label for="inputPassword">パスワード</label>
                    <input type="password" name="password" id="inputPassword" class="form-control mb-4" placeholder="Password" required>
                    <button class="w-100 btn btn-lg btn-primary mt-4" type="submit">ログイン</button>
                </form>
            </div>
        </div>


        <div  class="mt-2 text-center">
            <a href="{{ route('get_register') }}">会員登録はこちら</a>
        </div>

        <div  class="mt-5 text-center">
            <strong class="text-success">簡単ログイン</strong>は会員登録なしで<br>
            24時間限定のお試し用アカウントを作成します。
            <a href="{{ route('easy_user.waiting') }}" class="w-100 btn btn-lg btn-outline-success fw-bold">
                簡単ログインはこちら
            </a>
        </div>

    </main>


    <script src="{{ asset('js/app.js') }}" defer></script>
  </body>
</html>
