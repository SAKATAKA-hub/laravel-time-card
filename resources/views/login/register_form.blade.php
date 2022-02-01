<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録フォーム</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">


</head>

<body class="bg-light">

    <div class="container">
      <main class="d-flex flex-column align-items-center">
        <div class="py-5 text-center">
          <h2>新規会員登録</h2>
          <p class="lead">会員登録を行うと、サービスの利用が可能になります。登録は無料です。</p>
        </div>

        <div class="row g-3 w-50">
            <div class="col-lg-12">

                @if (session('error_alert'))
                    <div class="text-danger mb-3">※{{ session('error_alert') }}</div>
                @endif
                <h4 class="mb-3">情報の入力</h4>
                <form method="post" action="{{ route('post_register') }}" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="username" class="form-label">ユーザーネーム(必須)</label>
                            <input type="text" name="name" class="form-control" id="username"
                                value="{{old('name')}}" placeholder="氏名、又は任意のユーザーネーム" required>
                            <p style="height:1em;color:red;">
                                {{$errors->has('name')? $errors->first('name'): ''}}
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">メールアドレス(必須)</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{old('email')}}" placeholder="you@example.com">
                            <p style="height:1em;color:red;">
                                {{$errors->has('email')? $errors->first('email'): ''}}
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">パスワード(必須)</label>
                            <input type="password" name="password" class="form-control" id="address"
                                value="{{old('password')}}" placeholder="8文字以上、半角英数字のみ" required>
                            <p style="height:1em;color:red;">
                                {{$errors->has('password')? $errors->first('password'): ''}}
                            </p>
                            </div>

                        <div class="col-12">
                            <label for="address" class="form-label">パスワード(確認用・必須)</label>
                            <input type="password" name="password_confirmation" class="form-control" id="address"
                                value="" placeholder="8文字以上、半角英数字のみ" required>
                            <p style="height:1em;color:red;">
                                {{$errors->has('conf_password')? $errors->first('conf_password'): ''}}
                            </p>
                            </div>

                    </div>

                    <hr class="my-4">
                    <button class="w-100 btn btn-primary btn-lg" type="submit">新規会員登録</button>

                </form>
          </div>
        </div>
      </main>

      <footer class="my-5 pt-5 text-muted text-center text-small">
        <ul class="list-inline">
            <li class="list-inline-item"><button  class="btn btn-link" type="button" onclick="history.back()">戻る</button></li>
            <li class="list-inline-item"><a  class="btn btn-link" href="{{route( 'login_form' )}}">ログイン画面</a></li>
        </ul>
      </footer>
    </div>

    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
