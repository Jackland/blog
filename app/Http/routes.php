<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});







Route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    Route::any('/login', 'LoginController@login');//登录界面
    Route::any('/addPwd', 'LoginController@addPwd');//生成密码加密
    Route::get('/code',  'LoginController@code'); //验证码

});


Route::group(['prefix'=>'admin','middleware'=>['AuthAdmin'],'namespace'=>'Admin'], function () {
    Route::any('/index', 'IndexController@index'); //首页
    Route::any('/right', 'IndexController@right'); //右侧

    Route::any('/add', 'IndexController@add');
    Route::any('/img', 'IndexController@img');
    Route::any('/lst', 'IndexController@lst');
    Route::any('/tab', 'IndexController@tab');
    Route::any('/element', 'IndexController@element');
    Route::any('/edtpwd', 'IndexController@edtpwd');//修改密码


    Route::any('/pwd', 'LoginController@addPwd'); //生成密码

    Route::any('/loginout', 'LoginController@loginout');//退出

    Route::any('/uploads', 'CommonController@uploads');//文件上传的方法




    Route::resource('category', 'CategoryController');//分类的资源路由
    Route::resource('article', 'ArticleController');//文章的资源路由

    Route::post('links/changeorder', 'LinksController@changeOrder');
    Route::resource('links', 'LinksController');


    Route::any('cate/changeorder', 'CategoryController@changeorder');//修改排序的路由


});