@extends('layouts.admin')
@section('content')
    <script src="{{asset('resources/views/admin/plupload/plupload.full.min.js')}}"></script>
    <!--面包屑导航 开始-->
    <div class="crumb_warp">
        <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
        <i class="fa fa-home"></i> <a href="{{url('admin/right')}}">首页</a> &raquo;文章管理
    </div>
    <!--面包屑导航 结束-->
    <style>
        .uploadify{display:inline-block;}
        .uploadify-button{border:none; border-radius:5px; margin-top:8px;}
        table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
    </style>
    <!--结果集标题与导航组件 开始-->
    <div class="result_wrap">
        <div class="result_title">
            <h3>添加文章</h3>
            @if(count($errors)>0)
                <div class="mark">
                    @if(is_object($errors))
                        @foreach($errors->all() as $error)
                            <p>{{$error}}</p>
                        @endforeach
                    @else
                        <p>{{$errors}}</p>
                    @endif
                </div>
            @endif
        </div>
        <div class="result_content">
            <div class="short_wrap">
                <a href="{{url('admin/article/create')}}"><i class="fa fa-plus"></i>添加文章</a>
                <a href="{{url('admin/article')}}"><i class="fa fa-recycle"></i>全部文章</a>
            </div>
        </div>
    </div>
    <!--结果集标题与导航组件 结束-->

    <div class="result_wrap">
        <form action="{{url('admin/article')}}" method="post">
            {{csrf_field()}}
            <table class="add_tab">
                <tbody>
                <tr>
                    <th width="120">分类：</th>
                    <td>
                        <select name="cate_id">
                            @foreach($data as $d)
                                <option value="{{$d->cate_id}}">{{$d->_cate_name}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><i class="require">*</i> 文章标题：</th>
                    <td>
                        <input type="text" class="lg" name="art_title">
                    </td>
                </tr>
                <tr>
                    <th>编辑：</th>
                    <td>
                        <input type="text" class="sm" name="art_editor">

                    </td>
                </tr>
                <tr>

                    <th>缩略图：</th>
                    <td>
                        <a class="btn" id="btn">上传图片</a> 最大500KB，支持jpg，gif，png格式。
                        <ul id="ul_pics" class="ul_pics clearfix"></ul>

                    </td>
                </tr>
                <tr>
                    <th>关键词：</th>
                    <td>
                        <input type="text" class="lg" name="art_tag">
                    </td>
                </tr>
                <tr>
                    <th>描述：</th>
                    <td>
                        <textarea name="art_description"></textarea>
                    </td>
                </tr>

                <tr>
                    <th>文章内容：</th>
                    <td>
                        <script type="text/javascript" charset="utf-8" src="{{asset('resources/views/admin/ueditor/ueditor.config.js')}}"></script>
                        <script type="text/javascript" charset="utf-8" src="{{asset('resources/views/admin/ueditor/ueditor.all.min.js')}}"> </script>
                        <script type="text/javascript" charset="utf-8" src="{{asset('resources/views/admin/ueditor/lang/zh-cn/zh-cn.js')}}"></script>
                        <script id="editor" name="art_content" type="text/plain" style="width:860px;height:500px;"></script>
                        <script type="text/javascript">
                            var ue = UE.getEditor('editor');
                        </script>
                        <style>
                            .edui-default{line-height: 28px;}
                            div.edui-combox-body,div.edui-button-body,div.edui-splitbutton-body
                            {overflow: hidden; height:20px;}
                            div.edui-box{overflow: hidden; height:22px;}
                        </style>
                    </td>
                </tr>

                <tr>
                    <th></th>
                    <td>
                        <input type="hidden" name="art_thumb" value=""  id="srcval" >
                        <input type="submit" value="提交">
                        <input type="button" class="back" onclick="history.go(-1)" value="返回">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </div>

    <script>
        var uploader = new plupload.Uploader({ //创建实例的构造方法
            runtimes: 'html5,flash,silverlight,html4',
            //上传插件初始化选用那种方式的优先级顺序
            browse_button: 'btn',

            // 上传按钮
            url: "{{url('admin/uploads')}}",
            //远程上传地址
            flash_swf_url: "{{asset('/admin/plupload/Moxie.swf')}}",
            //flash文件地址
            silverlight_xap_url: "{{asset('/admin/plupload/Moxie.xap')}}",
            //silverlight文件地址
            filters: {
                max_file_size: '1500kb',
                //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
                mime_types: [ //允许文件上传类型
                    {
                        title: "files",
                        extensions: "jpg,png,gif,jpeg"
                    }]
            },
            multi_selection: true,
            //true:ctrl多文件上传, false 单文件上传
            init: {
                FilesAdded: function(up, files) { //文件上传前
                    if ($("#ul_pics").children("li").length > 30) {
                        alert("您上传的图片太多了！");
                        uploader.destroy();
                    } else {
                        var li = '';
                        plupload.each(files,
                                function(file) { //遍历文件
                                    li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
                                });
                        $("#ul_pics").append(li);
                        uploader.start();
                    }
                },
                UploadProgress: function(up, file) { //上传中，显示进度条
                    $("#" + file.id).find('.bar').css({
                        "width": file.percent + "%"
                    }).find(".percent").text(file.percent + "%");
                },
                FileUploaded: function(up, file, info) { //文件上传成功的时候触发

                    var data = JSON.parse(info.response);
                    var imgsrc=data.pic;
                    var arr= imgsrc.split('uploads');

                    var arrimg='uploads'+arr[1];

                    $('#srcval').val(arrimg);

                    $("#" + file.id).html("<div class='img'><img style='max-width: 350px; max-height:100px;' src='/" +arrimg + "'/></div><p>" + data.name + "</p>");
                },
                Error: function(up, err) { //上传出错的时候触发
                    alert(err.message);
                }
            }
        });
        uploader.init();

    </script>

@endsection
