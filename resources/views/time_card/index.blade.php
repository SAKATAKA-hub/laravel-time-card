@extends('layouts.base')

{{-- ページタイトル --}}
@php
    $page_title = "タイムカード";
@endphp
@section('title',$page_title)


{{-- パンくずリスト --}}
@section('breadcrumb','>'.$page_title)
{{-- {{ Breadcrumbs::render('note', $mypage_master, $note) }} --}}


{{-- 見出し --}}
@section('heading',$page_title)




{{-- styleタグ --}}
@section('style')
    <style>
        main #showTime{
            display: flex;
            align-items: center;

            height: 3rem;
            font-weight: bold;
            font-size: 1.5rem;
        }
        main #showTime #nowDay{
            font-size: 1.5rem;
        }
        main  #showTime #nowAmPm{
            margin-left: 1rem;
        }
        main #showTime #nowTime{
            margin-left: .5rem;
            font-size: 2.25rem;
        }
        main #showTime #nowSec{
            margin-left: .5rem;
        }


        main .time_card_container{
            text-align: center;
            max-width: 340px;
            margin: 0 auto;
        }
        main .time_card_container h4{
            margin: 0;
            height:3rem;
            line-height:3rem;
        }

        main  .time_card_container .card{
            border-radius: 0.5em;
            box-shadow: 0 2px 10px rgb(0 0 0 / 40%);
        }
    </style>
@endsection





{{-- scriptタグ --}}
@section('script')

    <script>
        'use strict';

        function showTime()
        {
            const now = new Date();
            const nowYear = now.getFullYear();
            const nowMonth = String(now.getMonth()+1).padStart(2,'0');
            const nowDate = String(now.getDate()).padStart(2,'0');
            const nowHour = String(now.getHours()).padStart(2,'0');
            const nowMin = String(now.getMinutes()).padStart(2,'0');
            const nowSec = String(now.getSeconds()).padStart(2,'0');
            const dayNum = String(now.getUTCDay());

            const DayArry =["(日)","(月)","(火)","(水)","(木)","(金)","(土)"];

            let ampm = "";
            if(nowHour<12){ampm = "AM";}
            else{ampm = "PM";}

            document.getElementById('nowDay').textContent = `${nowYear}年${nowMonth}月${nowDate}日${DayArry[dayNum]}`;
            document.getElementById('nowAmPm').textContent = ampm;
            document.getElementById('nowTime').textContent = `${nowHour % 12}:${nowMin}`;
            document.getElementById('nowSec').textContent = `:${nowSec}`;


            refresh();
        }
        function refresh(){setTimeout(showTime,1000);}
        showTime();

    </script>


@endsection




{{-- メインコンテンツ --}}
@section('main_content')
    <!--現在時刻の表示領域-->
    <div id="showTime" class="mb-3">
        <div id="nowDay"></div>
        <div id="nowAmPm"></div>
        <div id="nowTime"></div>
        <div id="nowSec"></div>
    </div>


    <!-- alert -->
    <div class="">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            おはようございます。<br>今日も一日がんばりましょう！
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            おはようございます。<br>今日も一日がんばりましょう！
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            今日も一日おつかれさまでした！
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>


    <div class="d-md-flex">
        <section class="mb-5">
            <ul class="list-group mb-5 w-md-100">
                <li class="list-group-item list-group-item-action list-group-item-primary">
                    <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                        style="background-color:pink; border:5px solid pink;" width="30" height="30"
                    >
                    <p class="d-inline ms-2">A disabled item</p>
                    <p class="d-inline ms-5 me-5 fw-bold">出勤中</p>
                </li>
                <li class="list-group-item list-group-item-action">
                    <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                        style="background-color:pink; border:5px solid pink;" width="30" height="30"
                    >
                    <p class="d-inline ms-2">A disabled item</p>
                    <p class="d-inline ms-5 me-5 fw-bold">出勤中</p>
                </li>
                <li class="list-group-item list-group-item-action">
                    <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                        style="background-color:pink; border:5px solid pink;" width="30" height="30"
                    >
                    <p class="d-inline ms-2">A disabled item</p>
                    <p class="d-inline ms-5 me-5 fw-bold">出勤中</p>
                </li>
                <li class="list-group-item list-group-item-action">
                    <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                        style="background-color:pink; border:5px solid pink;" width="30" height="30"
                    >
                    <p class="d-inline ms-2">A disabled item</p>
                    <p class="d-inline ms-5 me-5 fw-bold">出勤中</p>
                </li>
                <li class="list-group-item list-group-item-action">
                    <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                        style="background-color:pink; border:5px solid pink;" width="30" height="30"
                    >
                    <p class="d-inline ms-2">A disabled item</p>
                    <p class="d-inline ms-5 me-5 fw-bold">出勤中</p>
                </li>
                </ul>
        </section>



        <section class="mb-5" style="flex:1;">
            <div class="time_card_container">

                <div class="card" style="min-height:23rem;">

                    <!-- Work State -->
                    <div class="border-bottom">
                        <h4 class="fw-bold text-secondary">従業員を選択してください</h4>
                    </div>

                    <div class="card-body">
                        <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                        style="background-color:pink; border:16px solid pink;" width="100" height="100"
                        >

                        <div>
                            {{-- <h4 class="fw-bold text-success">出勤中</h4> --}}
                            {{-- <h4 class="fw-bold text-warning">休憩中</h4> --}}
                            <h4 class="fw-bold text-success">退勤中</h4>
                        </div>

                        <!-- button -->
                        <div class="d-grid gap-2 mt-3">
                            {{-- <button class="btn btn-outline-success btn-lg" type="button">勤務開始</button>
                            <button class="btn btn-outline-warning btn-lg" type="button">休憩開始開始</button> --}}
                            <button class="btn btn-outline-warning btn-lg" type="button">休憩終了</button>
                            <button class="btn btn-outline-danger btn-lg" type="button">勤務終了</button>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection





