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



Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Home\IndexController@index');
    Route::get('/cate/{cate_id}', 'Home\IndexController@cate');
    Route::get('/a/{art_id}', 'Home\IndexController@article');


    Route::any('admin/login', 'Admin\LoginController@login');//登录界面
    Route::any('admin/addPwd', 'Admin\LoginController@addPwd');//生成密码加密
    Route::get('admin/code',  'Admin\LoginController@code'); //验证码
});


Route::group(['prefix'=>'admin','middleware'=>['AuthAdmin'],'namespace'=>'Admin'], function () {
    Route::any('/index', 'IndexController@index'); //首页
    Route::any('/right', 'IndexController@right'); //右侧

    Route::any('/edtpwd', 'IndexController@edtpwd');//修改密码


    Route::any('/pwd', 'LoginController@addPwd'); //生成密码

    Route::any('/loginout', 'LoginController@loginout');//退出

    Route::any('/uploads', 'CommonController@uploads');//文件上传的方法


    Route::resource('category', 'CategoryController');//分类的资源路由
    Route::resource('article', 'ArticleController');//文章的资源路由


    Route::get('Domweb/getdata', 'DomwebController@getdata');


    Route::post('links/changeorder', 'LinksController@changeOrder');
    Route::resource('links', 'LinksController');

    Route::post('navs/changeorder', 'NavsController@changeOrder');
    Route::resource('navs', 'NavsController');

    Route::get('config/putfile', 'ConfigController@putFile');
    Route::post('config/changecontent', 'ConfigController@changeContent');
    Route::post('config/changeorder', 'ConfigController@changeOrder');
    Route::resource('config', 'ConfigController');


    Route::any('cate/changeorder', 'CategoryController@changeorder');//修改排序的路由


});