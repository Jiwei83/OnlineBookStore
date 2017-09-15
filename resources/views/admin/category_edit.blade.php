@extends('admin.master')

@section('content')
    <article class="page-container">
        <form class="form form-horizontal" id="form-category-edit">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="{{$category->name}}" placeholder="" id="name" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>编号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="number" class="input-text" value="{{$category->parent_id}}" placeholder="" id="category_no" name="category_no">
                    <input type="number" class="input-text" value="{{$category->id}}" id="id" name="id" hidden>

                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>上级类别：</label>
                <div class="formControls col-xs-8 col-sm-9"> <span class="select-box">
				<select name="parent_id" class="select">
                    <option value="{{$category->id}}">{{$category->parent->name}}</option>
                    @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                </select>
				</span> </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-3">
                    <input class="btn btn-primary radius" type="submit" value="&nbsp;&nbsp;提交&nbsp;&nbsp;">
                </div>
            </div>
        </form>
    </article>
@endsection

@section('my-js')
    <script type="text/javascript">
        $("#form-category-edit").validate({
            rules:{
                name:{
                    required:true,
                    minlength:2,
                    maxlength:16
                },
                category_no:{
                    required:true,
                }
            },
            onkeyup:false,
            focusCleanup:true,
            success:"valid",
            submitHandler:function(form){
                //$(form).ajaxSubmit();
//                var index = parent.layer.getFrameIndex(window.name);
//                //parent.$('.btn-refresh').click();
//                parent.layer.close(index);
                $('#form-category-edit').ajaxSubmit({
                    type: 'post', // 提交方式 get/post
                    url: '/admin/service/category/edit', // 需要提交的 url
                    dataType: 'json',
                    data: {
                        id: $('input[name=id]').val(),
                        name: $('input[name=name]').val(),
                        category_no: $('input[name=category_no]').val(),
                        parent_id: $('select[name=parent_id] option:selected').val(),
                        _token: "{{csrf_token()}}"
                    },
                    success: function(data) {
                        if(data == null) {
                            layer.msg('服务端错误', {icon:2, time:2000});
                            return;
                        }
                        if(data.status != 0) {
                            layer.msg(data.message, {icon:2, time:2000});
                            return;
                        }

                        layer.msg(data.message, {icon:1, time:2000});
                        parent.location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(status);
                        console.log(error);
                        layer.msg('ajax error', {icon:2, time:2000});
                    },
                    beforeSend: function(xhr){
                        layer.load(0, {shade: false});
                    },
                });

                return false;
            }

        });
    </script>
@endsection