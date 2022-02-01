<!doctype html>
<html lang="ja">
<head>
    <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title meta tags -->
        <title>ユーザー設定内容の変更</title>

    <!-- Styel link tags -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('css/layouts/base.css')}}">

        <style>
            #preview{
                width: 200px;
                height: auto;
            }
        </style>
</head>

<body>
    <main class="bg-light">
        <!-- main_container -->
        <div class="d-flex flex-column align-items-center">


            <!-- main_top_container -->
            <h2 class="p-3">ユーザー設定内容の変更</h2>


            <div class="row g-3 w-50">

                <div class="col-lg-12">

                    <h4 class="mb-3">ユーザー情報の変更</h4>
                    <form method="POST" action="{{ route('update_register') }}" class="needs-validation" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                        <div class="row g-3">
                            <div class="col-12">
                                <label for="username" class="form-label">ユーザーネーム</label>
                                <input type="text" name="name" class="form-control" id="username"
                                    value="{{old('name',Auth::user()->name)}}" placeholder="氏名、又は任意のユーザーネーム" required>
                                <p style="color:red;">
                                    {{$errors->has('name')? $errors->first('name'): ''}}
                                </p>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="email" name="email" class="form-control" id="email"
                                    value="{{old('email',Auth::user()->email)}}" placeholder="you@example.com" required>
                                <p style="color:red;">
                                    {{$errors->has('email')? $errors->first('email'): ''}}
                                </p>
                            </div>

                        </div>

                        <button class="mt-3 w-100 btn btn-primary btn-lg" type="submit">変更内容の保存</button>

                    </form>




                    <hr class="my-4">




                    <h4 class="mb-3">パスワードの変更</h4>
                    <form method="POST" action="{{ route('update_register') }}" class="needs-validation" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">

                        <div class="col-12">
                            <label for="password" class="form-label">新しいパスワード</label>
                            <input type="password" name="password" class="form-control" id="password"
                                value="" placeholder="8文字以上、半角英数字のみ" required>
                            <p style="color:red;">
                                {{$errors->has('password')? $errors->first('password'): ''}}
                            </p>
                        </div>

                        <div class="col-12">
                            <label for="password_confirmation" class="form-label">確認用パスワード</label>
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                                value="" placeholder="8文字以上、半角英数字のみ" required>
                            <p style="color:red;">
                                {{$errors->has('conf_password')? $errors->first('conf_password'): ''}}
                            </p>
                        </div>


                        <button class="mt-3 w-100 btn btn-primary btn-lg" type="submit">変更内容の保存</button>

                    </form>




                    <hr class="my-4">




                    {{-- <form  class="mb-5 text-center">
                        <input type="hidden" name="user" class="form-control" id="username">
                        <button type="submit" class="btn btn-link">退会の処理</button>
                    </form> --}}

                    <div  class="mb-5 text-center">
                        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#deleteModal">退会の処理</button>
                    </div>
                </div>


            </div>


        </div>


        <!-- Modal(ユーザー情報削除モーダル) -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('destroy_register')}}" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id}}" id="modalInputElement">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">退会の処理</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ユーザーの登録情報と投稿ノートを全て削除します。<br>
                            削除された情報は復元できません。<br>よろしいですか？
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                            <button type="submit" class="btn btn-outline-danger">退会</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </main>


    <script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
