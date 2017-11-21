<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IndexController extends CommonController
{
    public function index()
    {
//       $a=DB::connection()->getPdo();//验证数据库连接

        $id=Session::get('adminId');
        $name=Admin::find($id)['username'];

        return view('admin.index',compact('name'));
    }

    public function right() //后台首页右侧
    {
        return view('admin.right');

    }

    public function add() //添加页
    {
        return view('admin.add');

    }

    public function lst()//列表页
    {
        return view('admin/lst');
    }

    public function tab()//tab页面
    {
        return view('admin/tab');
    }

    public function img() //图片列表页
    {
        return view('admin/img');

    }

    public function element() //图片列表页
    {
        return view('admin/element');

    }


    public function edtpwd()
    {
        if($input=Input::all()){

            $rules = [
                'password' => 'required|between:6,20|confirmed',
                'password_old' => 'required',
                'password_confirmation' => 'required',

            ];
            $message = [
                'password.required' => '新密码不能为空！',
                'password.between' => '新密码必须在6-20位！',
                'password.confirmed' => '两次密码不一致！',

                'password_old.required' => '旧密码不能为空！',
                'password_confirmation.required'=>'确认密码不能为空！',

            ];
            $validator = Validator::make($input,$rules, $message);


            if($validator->fails()){
                return back()->with('errors',$validator->errors()->all());

            }else{
                if($input['password']!=$input['password_confirmation']){
                    return back()->with('msg','两次密码不一致');
                }

                $info=Admin::find(Session::get('adminId'));
                if(Crypt::decrypt($info['password'])!=$input['password_old']){
                    return back()->with('msg','旧密码错误！');
                }else{
                    $pwd=Crypt::encrypt($input['password']);
                    $info->password=$pwd;
                    $bool=$info->save();
                    if($bool){
                        return back()->with('msg','密码修改成功！');
                    }else{
                        return false;
                    }

                }
            }
        }else{
            return view('admin/edtpwd');
        }



    }





}
