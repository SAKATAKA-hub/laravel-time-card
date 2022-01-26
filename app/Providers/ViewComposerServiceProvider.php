<?php

namespace App\Providers;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::Composer('date_list','App\Http\ViewComposers\DateListComposer');
        View::Composer('month_list','App\Http\ViewComposers\MonthListComposer');
        View::Composer('parsonal_list','App\Http\ViewComposers\ParsonalListComposer');
        View::Composer('edit_work_record.index','App\Http\ViewComposers\EditWorkRecordComposer');

        View::Composer('*','App\Http\ViewComposers\NavMenuComposer');

        // Link1Composer
    }
}
