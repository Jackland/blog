<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class Admin extends Model
{
    protected $table='admin';

    //Ö¸¶¨id
    protected $primarykey='id';
    public $timestamps=false;

    public static function checkLogin()
    {

        $input=Input::all();



        $name=$input['username'];
        $pwd=(int)$input['password'];
        $data=Admin::where(['username'=>$name])->first();

        $adminpwd=Crypt::decrypt($data['password']);

        if($adminpwd == $pwd){
            Session::put('adminId', $data['id']);
            return true;
        }else{
            return false;
        }



    }

}
