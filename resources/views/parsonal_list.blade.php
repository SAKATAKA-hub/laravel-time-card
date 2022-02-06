@extends('layouts.base')

{{-- ページタイトル --}}
@php
    $page_title = "個人別勤怠一覧";
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

        <!--前月-->
        @php $befor_date_ob = $date_ob->copy()->subMonth(); @endphp
        <a href="{{route($rote_name,['month'=>$befor_date_ob->format('Y-m')])}}" class="me-3">前月</a>

        <!--表示中の年月 従業員名-->
        <div class="fs-3">
            {{$date_ob->format('Y年m月')}}

            <img src="{{asset('svg/employee.svg')}}" class="rounded-circle ms-3 mb-1" width="26" height="26"
                style="background-color:{{$employee->color}}; border:5px solid {{$employee->color}};' "
            >

            {{$employee->name}}
        </div>

        <!--翌月-->
        @php $next_date_ob = $date_ob->copy()->addMonth(); @endphp
        <a href="{{route($rote_name,['month'=>$next_date_ob->format('Y-m')])}}" class="ms-3">翌月</a>


        <!-- 年月変更モーダルボタン -->
        <button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#ChangeDateModal">
            {{-- <i class="bi bi-calendar2"></i> --}}
            <i class="bi bi-person-fill"></i>
            <div class="d-none d-md-inline ms-1">年月・従業員の選択</div>
        </button>


    </div>




    <!-- TableContainer -->
    <div class="table_container mb-5">

        <table class="table bg-white text-center" style="width: 900px;">
            <thead class="border-secondary">
                <tr>
                    <th scope="col">日 付</th>
                    <th scope="col">就 業</th>
                    <th scope="col">休 憩</th>
                    <th scope="col">勤務時間(h)</th>
                    <th scope="col">休憩時間(h)</th>
                    <th scope="col">労働時間(h)</th>
                    <th scope="col">深夜時間(h)</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($work_times as $work_time)
                <tr>
                    <td>{{$work_time->date_text}}</td>
                    <td>{{$work_time->text}}</td>
                    <td></td>

                    <td>{{$work_time->restrain_hour}}</td> <!-- 勤務時間(h) -->
                    <td>{{$work_time->break_hour}}</td> <!-- 休憩時間(h) -->
                    <td>{{$work_time->working_hour}}</td> <!-- 労働時間(h) -->
                    <td>{{$work_time->night_hour}}</td> <!-- 深夜時間(h) -->
                </tr>

                    @foreach ($work_time->break_times as $break_time)
                    <tr>
                        <td></td>
                        <td></td>
                        <td>{{$break_time->text}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforeach

                <!-- 勤務記録が存在しないとき -->
                @empty
                    <tr>
                        <th colspan="7" class="text-secondary">
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
                    <th scope="col">{{$total_times['restrain_hour']}}</th> <!-- 総勤務時間(h) -->
                    <th scope="col">{{$total_times['break_hour']}}</th> <!-- 総休憩時間(h) -->
                    <th scope="col">{{$total_times['working_hour']}}</th> <!-- 総労働時間(h) -->
                    <th scope="col">{{$total_times['night_hour']}}</th> <!-- 総深夜時間(h) -->
                </tr>
            </tfoot>

        </table>

    </div>




    <!-- 年月変更モーダル -->
    <div class="modal fade" id="ChangeDateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ChangeDateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route($rote_name)}}" method="GET">

                    <div class="modal-header">
                        <h5 class="modal-title" id="ChangeDateModalLabel">年月・従業員の選択</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <div class="modal-body">
                        <!--年月-->
                        <div class="mb-3">
                            <label for="formMonth" class="form-label">年月</label>
                            <input type="month" name="month" value="{{$date_ob->format('Y-m')}}" class="form-control" id="formMonth" aria-describedby="emailHelp">
                        </div>


                        <!--従業員-->
                        <div class="mb-3">
                            <label for="formEmployee" class="form-label">従業員</label>

                            <select class="form-select" name="employee_id" id="formEmployee">

                                @foreach ($employees as $employee)

                                    @if ($employee->id === $employee_id)
                                        <option value="{{$employee->id}}" selected>{{$employee->name}}</option>
                                    @else
                                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                                    @endif

                                @endforeach

                            </select>

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
