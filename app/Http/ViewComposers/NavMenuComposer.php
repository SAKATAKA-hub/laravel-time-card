<?php

namespace App\Http\ViewComposers;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class NavMenuComposer
{

    public function compose(View $view)
    {
        // dd($view->rote_name);

        # ナビメニューに表示するリンクデータ
        $links = [
            'time_card' =>
            [
                'href' => asset('time_card'),
                'text' => 'タイムカード',
                'activ_class' => '',
                'aria-current' => '',
            ],

            'date_list' =>
            [
                'href' => asset('date_list'),
                'text' => '日別勤怠管理表',
                'activ_class' => '',
                'aria-current' => '',
            ],

            'month_list' =>
            [
                'href' => asset('month_list'),
                'text' => '月別勤怠管理表',
                'activ_class' => '',
                'aria-current' => '',
            ],

            'parsonal_list' =>
            [
                'href' => asset('parsonal_list'),
                'text' => '個人別勤怠管理表',
                'activ_class' => '',
                'aria-current' => '',
            ],

            'edit_work_record' =>
            [
                'href' => asset('edit_work_record'),
                'text' => '勤怠修正',
                'activ_class' => '',
                'aria-current' => '',
            ],

            'edit_employee_list' =>
            [
                'href' => '',
                'text' => '従業員登録',
                'activ_class' => '',
                'aria-current' => '',
            ],
        ];

        # リンクデータにアクティブなリンク先のデータを更新
        if(
            ($view->rote_name !== NULL) &&
            ( array_key_exists($view->rote_name,$links ) )
        ){
            $links[$view->rote_name]['activ_class'] = 'active';
            $links[$view->rote_name]['aria-current'] = 'page';
        }


        # viewに変数を追加
        $view->with(['links' => $links,]);
    }


}
