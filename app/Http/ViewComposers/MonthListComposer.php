<?php

namespace App\Http\ViewComposers;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;


class MonthListComposer
{

    public function compose(View $view)
    {
        # viewに変数を追加
        $view->with(['rote_name' => 'month_list']);
    }

}
