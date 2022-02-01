@extends('layouts.base')

{{-- ページタイトル --}}
@php
    $page_title = "勤怠修正";
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
    <meta name="route_records_json" content="{{route('edit_work_record.records_json')}}">
    <meta name="route_validate_input_time" content="{{route('edit_work_record.validate_input_time')}}">
    <meta name="route_update" content="{{route('edit_work_record.update')}}">
    <meta name="route_destroy" content="{{route('edit_work_record.destroy')}}">

    <!-- param -->
    <meta name="user_id" content="{{$user_id}}">
    <meta name="date" content="{{$date}}">

    <style>
        .err_input{
            background-color: pink;
        }
    </style>

@endsection








{{-- scriptタグ --}}
@section('script')

    <!-- Vue.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <!-- Vuejs.js -->
    @include('edit_work_record.vuejs')

@endsection




{{-- メインコンテンツ --}}
@section('main_content')


    <!-- HeadingContainer -->
    <div class="d-flex align-items-center mb-1">

        <!--前日-->
        @php $befor_date_ob = $date_ob->copy()->subDay(); @endphp
        <a href="{{route($rote_name,['date'=>$befor_date_ob->format('Y-m-d')])}}" class="me-3">前日</a>

        <!--表示中の日付-->
        <div class="fs-3">{{$date_ob->format('Y年m月d日').$weeks[$date_ob->format('w')]}}</div>

        <!--翌日-->
        @php $next_date_ob = $date_ob->copy()->addDay(); @endphp
        <a href="{{route($rote_name,['date'=>$next_date_ob->format('Y-m-d')])}}" class="ms-3">翌日</a>

        <!-- 日付変更モーダルボタン -->
        <button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#ChangeDateModal">
            <i class="bi bi-calendar2"></i>
            <div class="d-none d-md-inline ms-1">日付の選択</div>
        </button>


    </div>



    <!-- テストコンテナー -->

    <div v-show="form_test">
        @include('test.edit_work_record')
    </div>




    <!-- TableContainer -->
    <div class="table_container mb-5">

        <!--テーブル-->
        <table class="table bg-white text-center" style="width: 900px;">
            <thead class="border-secondary">
                <tr>
                    <th scope="col">氏 名</th>
                    <th scope="col">就 業</th>
                    <th scope="col">休 憩</th>
                    <th scope="col">勤務時間(h)</th>
                    <th scope="col">休憩時間(h)</th>
                    <th scope="col">労働時間(h)</th>
                    <th scope="col">深夜時間(h)</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <!-- v-for worrk_times -->
            <tbody v-for="(work_time, w_index) in work_times"  class="border-0">
                <tr>
                    <th scope="row">@{{work_time.employee.name}}</th>
                    <td>@{{work_time.text}}</td>
                    <td></td>

                    <td>@{{work_time.restrain_hour}}</td> <!-- 勤務時間(h) -->
                    <td>@{{work_time.break_hour}}</td> <!-- 休憩時間(h) -->
                    <td>@{{work_time.working_hour}}</td> <!-- 労働時間(h) -->
                    <td>@{{work_time.night_hour}}</td> <!-- 深夜時間(h) -->

                    <td> <!-- 修正モーダルボタン('updateForm'.$w_index) -->
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal" @click="editStart(w_index)">
                            修正
                        </button>
                    </td>
                    <td> <!-- 削除モーダルボタン('destroyForm'.$w_index) -->
                        <button type="button"class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#destroyModal" @click="editStart(w_index)">
                            削除
                        </button>
                    </td>
                </tr>

                <!-- v-for break_times -->
                <tr v-for="(break_time, b_index) in work_time.break_times">
                    <td></td>
                    <td></td>
                    <td>@{{break_time.text}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- end v-for break_times -->


            </tbody>
            <!-- end v-for worrk_times -->


            <!-- 勤務記録が存在しないとき -->
            <tbody  v-show="!work_times.length" class="border-0">
                <tr>
                    <th colspan="9" class="text-secondary">
                        勤務記録がありません
                    </th>
                </tr>
            </tbody>


            <tfoot class="border-secondary" v-if="Object.keys(total_times).length">
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col">@{{total_times['restrain_hour']}}</th> <!-- 総勤務時間(h) -->
                    <th scope="col">@{{total_times['break_hour']}}</th> <!-- 総休憩時間(h) -->
                    <th scope="col">@{{total_times['working_hour']}}</th> <!-- 総労働時間(h) -->
                    <th scope="col">@{{total_times['night_hour']}}</th> <!-- 総深夜時間(h) -->
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>

        </table>

    </div>


    <!--
    -----------------------------------------
        Modal
    -----------------------------------------
    -->

    <!--  勤怠修正モーダル (id="editModal")  -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">


                <!-- modal-header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel" v-if="Object.keys(editing_work_time).length">
                        {{$date_ob->format('Y年m月d日').$weeks[$date_ob->format('w')]}} @{{editing_work_time.employee.name}}
                    </h5>
                </div>




                <!-- modal-body -->
                <div class="modal-body">
                    <table class="table bg-white" style="max-width: 900px;">

                        <!--出退勤時間-->
                        <tr>
                            <th><div class="d-flex align-items-center">
                                <div class="col-auto">
                                    <label class="me-2">出勤</label>
                                </div>
                                <div class="col-auto">
                                    <input class="form-control" type="time" v-model="editing_work_time.input_in" @blur="changeWorkTime">
                                </div>
                            </div></th>


                            <th><div class="d-flex align-items-center">
                                <div class="col-auto">
                                    <label class="me-2">退勤</label>
                                </div>
                                <div class="col-auto">
                                    <input class="form-control" type="time" v-model="editing_work_time.input_out" @blur="changeWorkTime">
                                </div>
                                <div class="col-auto">
                                    <span style="cursor:pointer;" @click="deleteInput(null)"><i class="bi bi-file-x fs-2 text-secondary"></i></span>
                                </div>
                            </div></th>


                            <td><!-- 削除ボタン --></td>
                        </tr>



                        <!--休憩時間-->
                        <!-- v-for break_times -->
                        <tr v-for="(break_time, b_index) in editing_work_time.break_times">
                                <td><div class="d-flex align-items-center">
                                    <div class="col-auto">
                                        <label class="ms-3 me-2">休憩開始</label>
                                    </div>
                                    <div class="col-auto">
                                        <input class="form-control" type="time" name="break_time_in[]" v-model="break_time.input_in" @blur="changeWorkTime">
                                    </div>
                                </div></td>


                                <td><div class="d-flex align-items-center">

                                    <div class="col-auto">
                                        <label class="ms-3 me-2">休憩終了</label>
                                    </div>
                                    <div class="col-auto">
                                        <input class="form-control" type="time" name="break_time_out[]" v-model="break_time.input_out" @blur="changeWorkTime">
                                    </div>
                                    <div class="col-auto">
                                        <span style="cursor:pointer;" v-if="(b_index===editing_work_time.break_times.length-1)&&(editing_work_time.input_out==='')" @click="deleteInput(b_index)"><i class="bi bi-file-x fs-2 text-secondary"></i></span>
                                    </div>
                                </div></td>


                                <td>
                                    <button type="button" class="btn btn-danger" @click="deleteBreakRecord(b_index)">削除</button>
                                </td>
                        </tr>
                        <!-- end v-for break_times -->
                    </table>


                    <div v-if="Object.keys(errors).length">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5>エラーメッセージ</h5>
                            <ul>
                                <li v-if="errors.valiWorkTime_in">@{{errors.valiWorkTime_in[0]}}</li>
                                <li v-if="errors['valiWorkTime_out']">@{{valiWorkTime_out[0]}}</li>
                            </ul>
                            <ul v-for="(break_time, i) in editing_work_time.break_times">
                                <li v-if="errors['valiBreakTimes_'+i+'_in']">@{{errors['valiBreakTimes_'+i+'_in'][0]}}</li>
                                <li v-if="errors['valiBreakTimes_'+i+'_out']">@{{errors['valiBreakTimes_'+i+'_out'][0]}}</li>
                            </ul>
                        </div>
                    </div>

                </div>




                <!-- modal-footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="editCancel()">閉じる</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" @click="updateWorkRecord()">更新</button>
                </div>


            </div>
        </div>
    </div>


    <!--  勤怠削除モーダル  -->
    <div class="modal fade" id="destroyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- modal-header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel" v-if="Object.keys(editing_work_time).length">
                        {{$date_ob->format('Y年m月d日').$weeks[$date_ob->format('w')]}} @{{editing_work_time.employee.name}}
                    </h5>
                </div>


                <!-- modal-body -->
                <div class="modal-body">
                    ”@{{editing_work_time.text}}”の勤務記録を削除します。よろしいですか？
                </div>

                <!-- modal-footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="editCancel()">閉じる</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" @click="deleteWorkRecord()">削除</button>
                </div>

            </div>
        </div>
    </div>


    <!-- 日付変更モーダル -->
    <div class="modal fade" id="ChangeDateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ChangeDateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route($rote_name)}}" method="GET">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ChangeDateModalLabel">日付の選択</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <div class="modal-body">
                        <!--日付-->
                        <div class="mb-3">
                            <label for="formDate" class="form-label">日付</label>
                            <input type="date" name="date" value="{{$date_ob->format('Y-m-d')}}" class="form-control" id="formDate">
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">閉じる</button>
                        <button type="submit" class="btn btn-primary">決定</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
