<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*
         * 说明只要存在session的情况下 才能进入后台 就是防墙
         * */
        $userInfo=Session::get('adminId'); //判断是否存在用户id
        if(empty($userInfo)){
            return redirect('admin/login');
        }



        return $next($request);
    }
}
