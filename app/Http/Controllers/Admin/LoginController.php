<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Admin;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

require_once 'resources/org/code/Code.class.php';

class LoginController extends CommonController
{
    public function login()
    {
//        dd($_SERVER); 打印服务的信息
        if($input=Input::all()){

            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];
            $message = [
                'username.required' => '用户名不能为空',
                'password.required' => '密码不能为空'
            ];
            $validator = Validator::make($input,$rules, $message);
            if($validator->fails()){
                return back()->with('errors',$validator->errors()->all());
            }

            $code=new \Code();
            $trueCode=$code->get();

            if($trueCode!=strtoupper($input['code'])){
                return back()->with('msg','验证码错误');
            }


            $bool=Admin::checkLogin();
            if($bool==true ){
                return redirect('admin/index');//跳转至后台首页
            }else{
                return back()->with('msg','用户名或者密码错误');
            }

        }else{


            return view('admin.login');
        }


    }
    public function addPwd() //生成初始化加密密码
    {
        $str='1234567';
        $pwd=Crypt::encrypt($str); //生成加密字符串

        dd($pwd);
        $de_pwd=Crypt::decrypt($pwd);//解密字符串
        echo $de_pwd;

    }

    public  function code()
    {
        $code =new \Code;
        $code->make();

    }

    /*退出登录*/

    public function loginout()
    {
       Session::put('adminId',null);
        return redirect('admin/login');
    }


}
