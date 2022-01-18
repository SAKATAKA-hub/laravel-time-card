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


@endsection
