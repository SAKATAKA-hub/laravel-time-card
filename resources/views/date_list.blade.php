@extends('layouts.base')

{{-- ページタイトル --}}
@php
    $page_title = "日別勤怠一覧";
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
                @for ($i = 0; $i < 10; $i++)
                <tr>
                    <th scope="row">山田　太郎</th>
                    <td>00日(月)</td>
                    <td>08:00-17:00</td>
                    <td>10:00-11:00(1.00)</td>
                    <td>9.00</td>
                    <td>2.00</td>
                    <td>7.00</td>
                    <td>0.00</td>
                    <td><button class="btn btn-warning">修正</button></td>
                    <td><button class="btn btn-danger">削除</button></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>14:00-15:00(1.00)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endfor
            </tbody>
            <tfoot class="border-secondary">
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col">90.00</th> <!--総勤務時間(h) -->
                    <th scope="col">20.00</th> <!--総休憩時間(h) -->
                    <th scope="col">70.00</th> <!--総労働時間(h) -->
                    <th scope="col">0.00</th> <!--総深夜時間(h) -->
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


@endsection
