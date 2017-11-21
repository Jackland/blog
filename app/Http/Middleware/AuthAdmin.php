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
         * ˵��ֻҪ����session������� ���ܽ����̨ ���Ƿ�ǽ
         * */
        $userInfo=Session::get('adminId'); //�ж��Ƿ�����û�id
        if(empty($userInfo)){
            return redirect('admin/login');
        }



        return $next($request);
    }
}
