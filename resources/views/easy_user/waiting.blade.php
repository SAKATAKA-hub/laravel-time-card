<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>データ作成中</title>

    <!-- favicon -->
    <link rel="icon" href="{{asset('svg/logo.svg')}}">

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body{
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* アイコンの回転 */
        .material-icons.rotate {
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% {transform: rotate(0deg);}
            100% {transform: rotate(360deg);}
        }

    </style>


</head>
<body onload="location.href='{{route('create_easy_user')}}'">
    <h1 class="text-center">


        <div>ただいま、お試し用のデータの作成中です。</div>
        <div>少々お待ちくださいませ。</div>

        <!-- ローディングアイコン -->
        <i class="material-icons rotate" style="font-size:10rem;">autorenew</i>
    </h1>
</body>
</html>
