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


@endsection








{{-- scriptタグ --}}
@section('script')


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
            {{-- <i class="bi bi-person-fill"></i> --}}
            <div class="d-none d-md-inline ms-1">日付の選択</div>
        </button>


    </div>




    <!-- TableContainer -->
    <div class="table_container mb-5">

        <table class="table bg-white text-center" style="width: 900px;">
            <thead class="border-secondary">
                <tr>
                    <th scope="col">氏 名</th>
                    <th scope="col">日 付</th>
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
            <tbody>

                @forelse ($work_times as $work_time)
                <tr>
                    <th scope="row">{{$work_time->employee->name}}</th>
                    <td>{{$work_time->date_text}}</td>
                    <td>{{$work_time->text}}</td>
                    <td></td>

                    <td>{{$work_time->restrain_hour}}</td> <!-- 勤務時間(h) -->
                    <td>{{$work_time->break_hour}}</td> <!-- 休憩時間(h) -->
                    <td>{{$work_time->working_hour}}</td> <!-- 労働時間(h) -->
                    <td>{{$work_time->night_hour}}</td> <!-- 深夜時間(h) -->

                    <td><button class="btn btn-warning">修正</button></td>
                    <td><button class="btn btn-danger">削除</button></td>
                </tr>

                    @foreach ($work_time->break_times as $break_time)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$break_time->text}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach

                <!-- 勤務記録が存在しないとき -->
                @empty
                    <tr>
                        <th colspan="10" class="text-secondary">
                            勤務記録がありません
                        </th>
                    </tr>
                @endforelse

            </tbody>
            <tfoot class="border-secondary">
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col">{{$total_times['restrain_hour']}}</th> <!-- 総勤務時間(h) -->
                    <th scope="col">{{$total_times['break_hour']}}</th> <!-- 総休憩時間(h) -->
                    <th scope="col">{{$total_times['working_hour']}}</th> <!-- 総労働時間(h) -->
                    <th scope="col">{{$total_times['night_hour']}}</th> <!-- 総深夜時間(h) -->
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>

        </table>

    </div>


    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        修正ボタン
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">0000年00月00日 山田　太郎</h5>
                    {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                </div>




                <div class="modal-body">

                    <table class="table bg-white">
                        <tr>
                            <th>出勤<input type="time" name="" value="08:00"></th>
                            <th>退勤 <input type="time" name="" value="08:00"></th>
                            <td></td>
                        </tr>
                        <tr>
                            <td>休憩開始 <input type="time" name="" value="08:00"></td>
                            <td>休憩終了 <input type="time" name="" value="08:00"></td>
                            <td><button class="btn btn-danger">削除</button></td>
                        </tr>
                        <tr>
                            <td>休憩開始 <input type="time" name="" value="08:00"></td>
                            <td>休憩終了 <input type="time" name="" value="08:00"></td>
                            <td><button class="btn btn-danger">削除</button></td>
                        </tr>
                    </table>

                    <div style="height:6rem;">
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            エラー：エラーメッセージ！
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                </div>




                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">戻る</button>
                </div>
            </div>
        </div>
    </div>






    <!-- 日付変更モーダルボタン -->
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