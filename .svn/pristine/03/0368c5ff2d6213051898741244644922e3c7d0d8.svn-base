<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>桂花中学182班|账号注册</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- 加载头部公共文件 -->
        <script type="text/javascript" src="__JS__/check.js"></script>
        <link rel="stylesheet" type="text/css" href="__JS__/DateTimePicker/jquery.datetimepicker.css" />
        <script src="__JS__/DateTimePicker/jquery.js"></script>
        <script src="__JS__/DateTimePicker/jquery.datetimepicker.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#getverify").click(function () {
                    telephone = $("#telephone").val();
                    url = "__URL__/pushverify";
                    pushverify();
                });
            });
        </script>
	</head>
	<body>
        <div class="register">
            <div class="register_center">
                <div class="register_bg"></div>
                <div class="register_form">
                    <div class="register_step">
                        <ul>
                            <li>
						    <span class="ly_tabtxt"><i class="iconnum iconnum1"></i>填写注册信息</span>
                                <span class="ly_tabbg" id="step1" style="width: 100%;"></span>
                            </li>
                            <li>
                                <span class="ly_tabtxt"><i class="iconnum iconnum2"></i>等待审核</span>
                                <span class="ly_tabbg" id="step2"></span>
                            </li>
                            <li>
                                <span class="ly_tabtxt"><i class="iconnum iconnum3"></i>通过审核</span>
                                <span class="ly_tabbg" id="step3"></span>
                            </li>
                            <li>
                                <span class="ly_tabtxt"><i class="iconnum iconnum4"></i>正常登录</span>
                                <span class="ly_tabbg" id="step4"></span>
                            </li>
                        </ul>
                    </div>
                    <div class="register_tips">备注：非182班同学成员无法通过审核</div>
                    <div class="register_content displayon">
                        <form  class="submit_form" id="register_form" action="__URL__/check_register" method="post">
                            <table>
                                <tr>
                                    <th><i>*</i>姓名：</th>
                                    <td>
                                        <span class="login_input" style="width:288px;"><label class="icon icon5"></label>
                                            <input type="text" class="login_text" id="nickname" name="nickname" value="" placeholder="请输入姓名"></span>
                                    </td>
                                    <td class="error_tips"><span class="icontips floatl"></span><span class="content"></span></td>
                                </tr>
                                <tr>
                                    <th><i>*</i>QQ：</th>
                                    <td>
                                        <span class="login_input" style="width:288px;"><label class="icon icon6"></label>
                                            <input type="text" class="login_text" id="qqcard" name="qqcard" value="" placeholder="请输入QQ号码"></span>
                                    </td>
                                    <td class="error_tips"><span class="icontips floatl"></span><span class="content"></span></td>
                                </tr>
                                <tr>
                                    <th>微信：</th>
                                    <td>
                                        <span class="login_input" style="width:288px;"><label class="icon icon7"></label>
                                            <input type="text" class="login_text" id="weixin" name="weixin" value="" placeholder="请输入微信号码"></span>
                                    </td>
                                    <td class="error_tips"><span class="icontips floatl"></span><span class="content"></span></td>
                                </tr>
                                <tr>
                                    <th><i>*</i>生日：</th>
                                    <td>
                                        <span class="login_input" style="width:288px;"><label class="icon icon8"></label>
                                            <input type="text" class="login_text" id="birthday" name="birthday" value="" placeholder="请选择您的生日"></span>
                                    </td>
                                    <td class="error_tips"><span class="icontips floatl"></span><span class="content"></span></td>
                                </tr>
                                <tr>
                                    <th><i>*</i>手机号码：</th>
                                    <td>
                                        <span class="login_input" style="width:288px;"><label class="icon icon4"></label>
                                            <input type="text" class="login_text" id="telephone" name="telephone" value="" placeholder="请输入手机号"></span>
                                    </td>
                                    <td class="error_tips"><span class="icontips floatl"></span><span class="content"></span></td>
                                </tr>
                                <tr>
                                    <th><i>*</i>短信验证码：</th>
                                    <td>
                                        <span class="login_input" style="width:188px;"><label class="icon icon3"></label>
                                            <input style="width:120px;" type="text" class="login_text floatl" id="dxverify" name="dxverify" value="" placeholder="请输入验证码"></span>
                                        <input value="点击获取" style="font-size: 14px; margin-top: 5px;" class="bluebtn verify_btn floatr" id="getverify" type="button"/>
                                    </td>
                                    <td class="error_tips"><span class="icontips floatl"></span><span class="content"></span></td>
                                </tr>
                                <tr>
                                    <th></th>
                                    <td><input type="submit" value="同意并注册" class="login_submit bluebtn"/></td>
                                </tr>
                            </table>
                        </form>
                        <div class="register_wait displayoff" id="register_wait">
                            恭喜您！注册成功，敬请等待账号审核结果，稍后我们将发送短信到您的手机上。
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $('#birthday').datetimepicker({
                lang:"ch",           //语言选择中文
                format:"Y-m-d",      //格式化日期
                timepicker:false    //关闭时间选项
            });
        </script>
    </body>
</html>
