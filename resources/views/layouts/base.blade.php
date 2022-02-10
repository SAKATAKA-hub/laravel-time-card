<!DOCTYPE html>
<html lang="en"  class="bg-secondary">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <!-- Title meta tags -->
    <title>@yield('title')</title>

    <!-- favicon -->
    <link rel="icon" href="{{asset('svg/logo.svg')}}">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            min-height: 80vh;
            max-width: 100vw;
        }

        .main_container{
            width: 100%;
            padding: 1rem ;
        }
        .main_container .table_container{
            width: 70vw;
            box-sizing: border-box;
            overflow:scroll;
            white-space:nowrap;
        }
        @media screen and (max-width:768px){
            .main_container .table_container{
                width: 100%;
            }
        }
        .main_container .table_container table{
            border:1px solid #ddd;
            /* margin: 0 auto; */
        }


    </style>

    <!-- popup -->
    <style>
        .popup{
            position: fixed;
            height: 0;
            overflow: hidden;

            bottom: 2rem;
            left: 1rem;
            right: 1rem;
            z-index: 1;
        }
        .popup.show{
            animation: popup 6s 1 2s;
        }
        @keyframes popup{
            0%{ }
            20%{ height: 4rem;}
            80%{ height: 4rem;}
            100%{ height: 0px; }
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
                    <a  href="{{route('time_card')}}"
                     class="navbar-brand text-center d-flex align-items-center flex-column"
                    >
                        <img src="{{asset('svg/logo.svg')}}" alt="" width="30" height="30">
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


    <main  id="app" class="d-flex">

        <!-- Popup -->
        @if ( session('popup_message') )
        <div class="popup show">
            <div class="alert alert-success fw-bold" role="alert" style="height:60px;">
                {{session('popup_message')}}
            </div>
        </div>
        @endif



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
                <a href="{{route('time_card')}}"><i class="bi bi-house-fill"></i>ホーム</a>
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
