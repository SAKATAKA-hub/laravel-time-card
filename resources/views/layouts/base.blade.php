<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <!-- Title meta tags -->
        <title>@yield('title')</title>

    <!-- favicon -->
        <link rel="icon" href="{{asset('svg/logo.svg')}}">

    <!-- Styel link tags -->
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
        <style>

            /*ナビメニュー　表示中*/
            .flex-column .active{
                font-size: 1em;
                font-weight: bold;
                border-radius: 1.5em 1.5em;
                color:#fff;
                background-color: rgb(0, 123, 255);
            }



            main{
                min-height: 100vh;
            }

            .main_container{
                max-width: 100%;
                padding: 1rem ;
            }
            .main_container .table_container{
                max-width: 100%;
                margin: 0 auto;
                box-sizing: border-box;
                overflow:scroll;
                white-space:nowrap;
            }
            .main_container .table_container table{
                border:1px solid #ddd;
            }

        </style>
        @yield('style')

</head>

<body>




    <header class="border-bottom border-secondary">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">


            <div class="container-fluid">
                <!-- ロゴ　アイコン -->
                <h1 class="navbar-brand">
                    <a class="navbar-brand d-flex align-items-center" href="">
                        <img src="{{asset('svg/logo.svg')}}" alt="" width="30" height="30" class="d-inline-block align-top">
                        <div class="text-primary fw-bold ms-1">Time Card</div>
                    </a>
                </h1>


                <div class="d-flex">

                    <!-- ユーザーメニュー -->
                    @include('includes.user_menu')

                    <!-- ハンバーガーメニュー　ボタン -->
                    <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>


                </div>
            </div>


            <!-- ナビメニュー(ハンバーガーメニューの中身) -->
            <div class="collapse navbar-collapse d-md-none" id="navbarNav">
                <nav class="d-md-none">
                    @include('includes.nav_menu')
                </nav>
            </div>


        </nav>
    </header>


    <main  class="d-flex">




        <!--------------------------
         サイドナビメニュー
        ---------------------------->
        <aside class="border-secondary d-none d-md-block">

            <nav style="min-width: 12rem;">
                @include('includes.nav_menu')
            </nav>

        </aside>





        <!--------------------------
         main　コンテナー
        ---------------------------->
        <div class="main_container">


            <!-- パンくずリスト -->
            <div class="mt-2">
                <a href=""><i class="bi bi-house-fill"></i>ホーム</a>
                <div class="d-inline-block">@yield('breadcrumb')</div>
            </div>


            <!--　見出し　-->
            <h2 class="mt-3 mb-3 fw-bold">@yield('heading')</h2>


            <!-- メインコンテンツ -->
            @yield('main_content')




        </div>




    </main>





    <footer class="bg-secondary p-1 text-center">

        <p class="text-white">&copy SAKAI TAKAHIRO</p>

    </footer>



    <!-- Script tags -->
        <script src="{{ asset('/js/app.js') }}"></script>
        @yield('script')



</body>
</html>
