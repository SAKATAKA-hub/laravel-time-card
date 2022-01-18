<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>タイムカード</title>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <style>
        main{
            height: 100vh;
        }
            main h4{
                margin: 0;
                height:3rem;
                line-height:3rem;
            }
            main .container{
                min-width: 340px;
                margin: 3rem auto;
            }
            main .card{
                border-radius: 0.5em;
                box-shadow: 0 2px 10px rgb(0 0 0 / 40%);
            }
    </style>

</head>
<body class="text-center bg-white">

    <header class="h3 fw-bold border-bottom pt-3 pb-3">
        タイムカード
    </header>


    <main>
        <div class="container">

            <div>

            </div>

            <div class="card" style="min-height:23rem;">

                <!-- Work State -->
                <div class="border-bottom">
                    <h4 class="fw-bold text-secondary">従業員を選択してください</h4>

                    {{-- <h4 class="fw-bold text-success">退勤中</h4> --}}
                    {{-- <h4 class="fw-bold text-warning">休憩中</h4> --}}
                    {{-- <h4 class="fw-bold text-danger">退勤中</h4> --}}
                </div>

                <div class="card-body">
                    <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                    style="background-color:pink; border:16px solid pink;" width="100" height="100"
                    >

                    <select class="form-select mb-3" aria-label="Default select example">

                        <option selected>選択してください</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>

                    </select>

                    <!-- alert -->
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        おはようございます。<br>今日も一日がんばりましょう！
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    {{-- <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        おはようございます。<br>今日も一日がんばりましょう！
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        おはようございます。<br>今日も一日がんばりましょう！
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
 --}}

                </div>
            </div>

            <!-- button -->
            <div class="d-grid gap-2 mt-3">
                {{-- <button class="btn btn-outline-success btn-lg" type="button">勤務開始</button> --}}
                <button class="btn btn-warning btn-lg" type="button">休憩開始開始</button>
                {{-- <button class="btn btn-warning btn-lg" type="button">休憩終了</button> --}}
                <button class="btn btn-outline-danger btn-lg" type="button">勤務終了</button>
            </div>

            <button class="btn btn-link mt-5" onclick="window.close();">閉じる</button>

        </div>
    </main>


    <footer  class="border-top">
        footer
    </footer>

    <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
