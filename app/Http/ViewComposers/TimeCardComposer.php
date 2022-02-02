<?php

namespace App\Http\ViewComposers;
use Illuminate\View\View;


class TimeCardComposer
{

    public function compose(View $view)
    {
        # viewに変数を追加
        $view->with(['rote_name' => 'time_card']);
    }

}
