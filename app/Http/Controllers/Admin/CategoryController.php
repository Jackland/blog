<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class CategoryController extends CommonController
{
   //资源型路由

    //get 数据
    public function index()
    {

        $cateModel=new Category();

        $treeData=$cateModel->tree();//调用的方法
        return view('admin.category.index')->with('data',$treeData);
        
    }





    //post 提交的的方法
    public function store()
    {
        $input=Input::except('_token');
        $res=Category::create($input);

        $rules=['cate_name'=>'required'];

        $message=['cate_name.required'=>'分类名称不能为空'];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){
            if($res){
                return redirect('admin/category');
            }else{
                return back()->with('errors','数据填充失败，请稍后重试！');
            }
        }else{

            return back()->withErrors($validator);
        }
    }

    //get.admin/category/create   添加分类
    public function create()
    {
       $data=Category::where('cate_pid',0)->get();


        return view('admin/category/add',compact('data'));



    }

    //DELETE admin/category/{category}
    // 删除单个分类 资源型路由的时候 有点要指明提交的方法 譬如 这个删除的方法 就要将method 方法传过来
    //同时还要将表单随机生成的crsf 传过来
    public function destroy($cate_id)
    {
        $re = Category::where('id',$cate_id)->delete();
        Category::where('cate_pid',$cate_id)->update(['cate_pid'=>0]);
        if($re){
            $data = [
                'status' => 0,
                'msg' => '分类删除成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类删除失败，请稍后重试！',
            ];
        }
        return $data;

    }
    //get admin/category/{category}
    public function show()
    {

    }



    //admin.category.edit
    public function edit($cate_id) //加载修改文章分类的方法
    {
        $field = Category::find($cate_id);
        $data = Category::where('cate_pid',0)->get();
        return view('admin.category.edit',compact('field','data'));
    }

    //admin.category.update
    public function update($cate_id) //修改文章时候以put提交
    {
        $input = Input::except('_token','_method');
        $re = Category::where('id',$cate_id)->update($input);
        if($re){
            return redirect('admin/category');
        }else{
            return back()->with('errors','分类信息更新失败，请稍后重试！');
        }
    }


    //修改排序的方法
    public function changeorder()
    {
        $input=Input::all();

        $objcate=Category::find($input['cate_id']);
        $objcate->cate_order = $input['cate_order'];
        $re = $objcate->update();

        if($re){
            $data = [
                'status' => 0,
                'msg' => '分类排序更新成功！',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg' => '分类排序更新失败，请稍后重试！',
            ];
        }
        return $data;


    }

    
    
}
