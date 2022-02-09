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
    <!-- token -->
    <meta name="token" content="{{ csrf_token() }}">
    <!-- route -->
    <meta name="route_employeees_json" content="{{route('time_card.employeees_json')}}">
    <meta name="route_work_in" content="{{route('time_card.work_in')}}">
    <meta name="route_break_in" content="{{route('time_card.break_in')}}">
    <meta name="route_break_out" content="{{route('time_card.break_out')}}">
    <meta name="route_work_out" content="{{route('time_card.work_out')}}">

    <!-- param -->
    <meta name="user_id" content="{{$user_id}}">

    <style>
        [v-cloak]{
            display: none;
            opacity: 0;
        }

        main #showTime{
            text-align: center;
            font-weight: bold;
        }
        main #showTime #nowDay{
            font-size: 1.0rem;
        }
        main  #showTime #nowAmPm{
            display: inline;
            margin-left: 1.5rem;
            font-size: 1.5rem;
        }
        main #showTime #nowTime{
            display: inline;
            margin-left: .5rem;
            font-size: 3rem;
        }
        main #showTime #nowSec{
            display: inline;
            margin-left: .5rem;
            font-size: 1.5rem;
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

        main .time_card_container .card{
            border-radius: 0.5em;
            box-shadow: 0 2px 10px rgb(0 0 0 / 40%);
        }
        main .time_card_container .card .card-body{
            border-radius: 0 0 0.5em 0.5em;
            background: #fff;
        }
    </style>
@endsection





{{-- scriptタグ --}}
@section('script')

    <!-- Vue.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

    @include('time_card.vuejs')

@endsection




{{-- メインコンテンツ --}}
@section('main_content')

    <div v-show="form_test" class="cloak" v-cloak>
        <div class="mb-3 p-3 bg-white">
            <h3 class="border-bottom mb-3">JSONテスト</h3>

            <ul>
                <li>
                    <h5 class="d-inline">従業員基本JSONデータの取得</h5>
                    <form action="{{route('time_card.employeees_json')}}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <button>実行</button>
                    </form>
                </li>

                <li>
                    <h5 class="d-inline">勤務開始処理(work_in)</h5>
                    <form action="{{route('time_card.work_in')}}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="employee_id" :value="active_employee.id">
                        <button>実行</button>
                    </form>
                </li>


            </ul>


        </div>
    </div>


    <div class="d-md-flex"  v-cloak>
        <section class="mb-5" :class="{'d-none': !mounted}" style="flex:1;">

            <!--現在時刻の表示領域-->
            <div id="showTime" class="mt-3">
                <div id="nowDay"></div>
                <div id="nowAmPm"></div>
                <div id="nowTime"></div>
                <div id="nowSec"></div>
            </div>

            <div class="time_card_container">

                <!-- v-if 従業員選択中 -->
                <div class="card" style="min-height:23rem;"
                 v-if="Object.keys(active_employee).length"
                 :class="{
                    'bg-secondary':active_employee.work_status===0,
                    'bg-success':active_employee.work_status===1,
                    'bg-warning':active_employee.work_status===2,
                 }"
                >
                    <div class="border-bottom" style="height:3rem; line-height:3rem;">
                        <h4 class="fw-bold text-light" v-if="active_employee.work_status===0">退勤中</h4>
                        <h4 class="fw-bold text-light" v-if="active_employee.work_status===1">出勤中</h4>
                        <h4 class="fw-bold" v-if="active_employee.work_status===2">休憩中</h4>
                    </div>

                    <div class="card-body">

                        <div>
                            <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                             v-if="active_employee.work_status===0"
                             style="background-color:#6c757d; border:16px solid #6c757d;" width="100" height="100"
                            >
                            <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                             v-else
                             :style=" 'background-color:'+active_employee.color+'; border:16px solid '+active_employee.color+';' "
                             width="100" height="100"
                            >
                        </div>

                        <div>
                            <h4 class="fw-bold"
                             :class="{'text-secondary':active_employee.work_status===0}"
                            >@{{active_employee.name}}</h4>
                        </div>

                        <!-- alert -->
                        <div class="alert alert-dismissible fade show" role="alert"
                            v-if="alert_index !== null" :class="alerts[alert_index].color"
                        >
                            @{{alerts[alert_index].message}}
                            {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                        </div>


                        <!-- button -->
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-success btn-lg" type="button"
                             v-if="active_employee.work_status===0"
                             @click="workIn()"
                            >勤務開始</button>
                            <button class="btn btn-warning btn-lg" type="button"
                             v-if="active_employee.work_status===1"
                             @click="breakIn()"
                            >休憩開始</button>
                            <button class="btn btn-warning btn-lg" type="button"
                             v-if="active_employee.work_status===2"
                             @click="breakOut()"
                            >休憩終了</button>
                            <button class="btn btn-danger btn-lg" type="button"
                             v-if="active_employee.work_status===1"
                             @click="workOut()"
                            >勤務終了</button>
                        </div>

                    </div>

                </div>


                <!-- v-else 従業員が選択されていない -->
                <div class="card" style="min-height:23rem;" v-else>
                    <div class="border-bottom" style="height:3rem; line-height:3rem;">
                    </div>

                    <div class="card-body">
                        <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                         style="background-color:#6c757d; border:16px solid #6c757d;" width="100" height="100"
                        >

                        <div>
                            <h4 class="fw-bold text-secondary">従業員を選択してください</h4>
                        </div>
                    </div>

                </div>

            </div>
        </section>



        <section class="mb-5">
            <ul class="list-group mb-5 w-md-100">

                <li class="list-group-item list-group-item-action d-flex justify-content-between  align-items-center"
                 v-for="(employee, e_index) in employees"
                 :class="{'list-group-item-primary':active_index===e_index}"
                 @click="selectEmployee(e_index)"
                >
                    <div class="ms-2 me-auto">

                        <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3"
                            v-if="employee.work_status===0"
                            style="background-color:#6c757d; border:5px solid #6c757d;" width="30" height="30"
                        ><!-- 退勤中 背景色:secondary -->
                        <img src="{{asset('svg/employee.svg')}}" class="rounded-circle mt-3 mb-3" width="30" height="30"
                            v-else
                            :style=" 'background-color:'+employee.color+'; border:5px solid '+employee.color+';' "
                        ><!-- 出勤中 背景色:employee_color -->

                        <p class="d-inline ms-2" :class="{'text-secondary':employee.work_status===0}">@{{employee.name}}</p>
                    </div>

                    <div v-if="employee.work_status === 0" class="ms-5 me-5 fw-bold text-secondary">退勤中</div>
                    <div v-if="employee.work_status === 1" class="ms-5 me-5 fw-bold text-success">出勤中</div>
                    <div v-if="employee.work_status === 2" class="ms-5 me-5 fw-bold text-warning">休憩中</div>

                </li>

            </ul>
        </section>
    </div>
@endsection





