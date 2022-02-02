<?php

namespace App\Http\ViewComposers;
use Illuminate\View\View;


class DateListComposer
{

    public function compose(View $view)
    {
        # viewに変数を追加
        $view->with(['rote_name' => 'date_list']);
    }

}
