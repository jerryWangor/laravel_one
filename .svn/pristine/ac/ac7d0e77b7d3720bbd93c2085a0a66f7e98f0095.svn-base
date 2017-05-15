/**
 * Created by wangjin on 2016/1/30.
 * 本页面主要是存放一些公共的操作
 */

    //设置全局变量
    var formFlag=false; //表单标识
    var ajaxFlag=false; //表单标识
    var checkboxObj = new Object(); //定义checkbox对象
    var minWidth=1220,minHeight=620,maxWidth=1920,maxHeight=1080; //浏览器最大/小宽度和高度

    /**
     * 当DOM加载的时候开始执行
     */
    $(document).ready(function() {

        //ajax返回信息框
        $("<div id=\"ajaxReturnTips\"></div>").css({ "position": "absolute", "left": "50%", "top": "50%", "text-align": "center", "margin-left": "-100px", "margin-top": "-25px", "width": "200px", "height": "50px", "line-height": "50px", "background-color": "#F0F9F9", "opacity": "0.8", "display": "none", "z-index": "90009" }).prependTo("body");

        //当浏览器改变大小时
        $(window).resize(function() {
            setMainArea();
        });

        //给所有ajax请求加上_token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //点击功能菜单实现下拉和收缩
        $(".side li .title").click(function(e) {
            var dropdown = $(this).parent().next();
            //$(".menulist").not(dropdown).slideUp("slow"); //一个或多个需要从匹配集中删除的 DOM 元素。意思就是让非当前元素全部收起来
            dropdown.slideToggle("slow");
            //判断是否有class on
            if($(this).hasClass("on")) {
                $(this).removeClass("on");
            } else {
                $(this).addClass("on");
            }
            e.preventDefault(); //阻止元素发生默认的行为
        });
        $(".menulist ul li").hover(function() {
            $(this).addClass("li_hover");
        },function () {
            $(this).removeClass("li_hover");
        }).click(function() {
            $("li").each(function() { // 用each循环去改变li标签的class
                $(this).removeClass("li_on");
            });
            $(this).addClass("li_on");
        });

        //登录表单的验证
        $("#login_form").submit(function() {
            var checkResult = checkForm($(this)); //如果表单元素检查成功返回true
            if(checkResult == true) {
                FormSubmit($(this), 'login', 1);
            }
            return false; //防止表单再次提交
        });

        /**
         * 鼠标移到action按钮实现更换背景图片
         */
        $(".action_btn").hover(
            function () {
                $(this).css("background-color", "#CBEFFB");
            },
            function () {
                $(this).css("background-color", "");
            }
        );

        /**
         * 表单input框获取焦点和失去焦点显示提示信息
         */
        $(".user_table input").focus(function() {
            var idname = $(this).attr("id");
            var content = $(this).parents("tr").find(".content");
            var icontips = $(this).parents("tr").find(".icontips");
            icontips.removeClass("icontips1 icontips2 icontips3 icontips4").addClass("icontips1");
            if(idname=="getverify") { //如果是获取验证码
                icontips.removeClass("icontips1 icontips2 icontips3 icontips4");
                content.html("");
                return false;
            }
            switch(idname) {
                case "news_title":
                    content.html("文章标题不得超过30个字!");
                    break;
                case "account":
                    content.html("4~18个字母、数字、下划线和减号，必须以字母开头!");
                    break;
                case "password":
                    content.html("6~16位的密码!");
                    break;
                case "nickname":
                    content.html("2~6个汉字，中文名字!");
                    break;
                case "qqcard":
                    content.html("2~12个数字，QQ号!");
                    break;
                case "weixin":
                    content.html("6~20个字母、数字、下划线和减号，必须以字母开头!");
                    break;
                case "email":
                    content.html("QQ邮箱,163邮箱等!");
                    break;
                case "birthday":
                    content.html("选择您的生日日期!");
                    break;
                case "telephone":
                    content.html("请输入您的电话号码!");
                    break;
                case "dxverify":
                    content.html("短信验证码!");
                    break;
                case "address":
                    content.html("请输入常用联系地址,50个字以内!");
                    break;
                case "company":
                    content.html("请输入您的公司名称,30个字以内!");
                    break;
                case "telephones":
                    content.html("请输入您的电话号码,多个以','隔开!");
                    break;
            }
        }).blur(function() {
            var errortext = '';
            var idname = $(this).attr("id");
            var value = trim($(this).val());
            var content = $(this).parents("tr").find(".content");
            var icontips = $(this).parents("tr").find(".icontips");
            if(idname=="getverify") { //如果是获取验证码
                icontips.removeClass("icontips1 icontips2 icontips3 icontips4");
                content.html("");
                return false;
            }
            //先判断是否为空
            if(!value) {
                icontips.removeClass("icontips1 icontips2 icontips3 icontips4").addClass("icontips2");
                content.html("内容不能为空!");
                return false;
            }
            switch(idname) {
                case "news_title":
                    if(!check_newstitle(value)) errortext = "文章标题格式不正确!";
                    break;
                case "account":
                    if(!check_user(value)) errortext = "用户名格式不正确!";
                    break;
                case "password":
                    if(!check_pwd(value)) errortext = "密码格式不正确!";
                    break;
                case "nickname":
                    if(!check_nickname(value)) errortext = "姓名格式不正确!";
                    break;
                case "qqcard":
                    if(!check_qqcard(value)) errortext = "QQ号格式不正确!";
                    break;
                case "weixin":
                    if(!check_weixincard(value)) errortext = "微信号格式不正确!";
                    break;
                case "email":
                    if(!check_email(value)) errortext = "邮箱格式不正确!";
                    break;
                case "birthday":
                    if(!check_birthday(value)) errortext = "生日日期格式不正确!";
                    break;
                case "telephone":
                    if(!check_telephone(value)) errortext = "电话号码格式不正确!";
                    break;
                case "dxverify":
                    if(!check_dxverify(value)) errortext = "短信验证码格式不正确!";
                    break;
            }
            if(errortext == '') {
                icontips.removeClass("icontips1 icontips2 icontips3 icontips4").addClass("icontips3");
                content.html("");
            } else {
                icontips.removeClass("icontips1 icontips2 icontips3 icontips4").addClass("icontips2");
                content.html(errortext);
            }
        });

    });