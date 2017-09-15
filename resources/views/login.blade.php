@extends('master')

@include('component.loading')

@section('title', '登录')

@section('content')
    <div class="weui_cells_title"></div>
    <div class="weui_cells weui_cells_form">
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">帐号</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="tel" placeholder="邮箱或手机号" name="username"/>
            </div>
        </div>
        <div class="weui_cell">
            <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="password" placeholder="不少于6位" name="password"/>
            </div>
        </div>
        <div class="weui_cell weui_vcode">
            <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
            <div class="weui_cell_bd weui_cell_primary">
                <input class="weui_input" type="text" placeholder="请输入验证码" name="validate_code"/>
            </div>
            <div class="weui_cell_ft">
                <img src="/service/validate_code/create" class="bk_validate_code"/>
            </div>
        </div>
    </div>
    <div class="weui_cells_tips"></div>
    <div class="weui_btn_area">
        <a class="weui_btn weui_btn_primary" href="javascript:" onclick="onLoginClick();">登录</a>
    </div>
    <a href="/register" class="bk_bottom_tips bk_important">没有帐号? 去注册</a>
@endsection

@section('my-js')
    <script type="text/javascript">
        $('.bk_validate_code').click(function () {
            $(this).attr('src', '/service/validate_code/create?random=' + Math.random());
        });
    </script>
    <script type="text/javascript">
        function onLoginClick() {
            var username = $('input[name=username]').val();
            var password = $('input[name=password]').val();
            var validate_code = $('input[name=validate_code]').val();
            if (validate_code == '') {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('验证码不能为空!');
                setTimeout(function () {
                    $('.bk_toptips').hide();
                }, 2000);
                return false;
            }
            if (validate_code.length != 4) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('验证码为4位!');
                setTimeout(function () {
                    $('.bk_toptips').hide();
                }, 2000);
                return false;
            }
            if (username == '' || password == '') {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('Username or password empty!');
                setTimeout(function () {
                    $('.bk_toptips').hide();
                }, 2000);
                return false;
            }
            if (password.length < 6) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('密码不能少于6位');
                setTimeout(function () {
                    $('.bk_toptips').hide();
                }, 2000);
                return false;
            }
            if (username.indexOf('@') >= 0) {
                if (verifyEmail(username) == false) {
                    return;
                }
            }
            if(username.indexOf('@') == 0) {
                if (verifyPhone(username) == false) {
                    return;
                }
            }
            $.ajax({
                type: "POST",
                url: '/service/login',
                dataType: 'json',
                cache: false,
                data: {
                    username: username, password: password,
                    validate_code: validate_code, _token: "{{csrf_token()}}"
                },
                success: function (data) {
                    if (data == null) {
                        $('.bk_toptips').show();
                        $('.bk_toptips span').html('服务端错误');
                        setTimeout(function () {
                            $('.bk_toptips').hide();
                        }, 2000);
                        return;
                    }
                    if (data.status != 0) {
                        $('.bk_toptips').show();
                        $('.bk_toptips span').html(data.message);
                        setTimeout(function() {$('.bk_toptips').hide();}, 2000);
                        return;
                    }

                    $('.bk_toptips').show();
                    $('.bk_toptips span').html('登录成功');
                    setTimeout(function () {
                        $('.bk_toptips').hide();
                    }, 2000);
                    location.href = '{{$return_url}}';
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                }
            });

        }

        function verifyPhone(username) {
            // 手机号格式
            if (username.length != 11 || username[0] != '1') {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('手机格式不正确');
                setTimeout(function () {
                    $('.bk_toptips').hide();
                }, 2000);
                return false;
            }
            return true;
        }

        function verifyEmail(username) {
            // 邮箱格式
            if (username.indexOf('@') == -1 || username.indexOf('.') == -1) {
                $('.bk_toptips').show();
                $('.bk_toptips span').html('邮箱格式不正确');
                setTimeout(function () {
                    $('.bk_toptips').hide();
                }, 2000);
                return false;
            }

            return true;
        }

    </script>
@endsection
