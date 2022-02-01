<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;


class DeleteEasyUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 24時間後の日時
        $date_time = Carbon::parse('-1 day')->format('Y-m-d H:i:s');

        # 24h経過した簡単ユーザー登録ユーザーの削除
        $users = User::where('easy_user',1)
        ->where('created_at','<',$date_time)
        ->get();

        if(count($users))
        {
            foreach ($users as  $user)
            {
                $user->delete();
            }

        }

        return $next($request);
    }
}
