<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>答题页面</title>
        <!-- 引入标签库 -->
        <tagLib name="html" />
        <!-- 加载头部公共文件 -->
        <?php require_once(HEADER_URL); ?>
        <style>
        .login_list { font-size: 18px; }
        .uid,.type,.limit { width: 100px; height: 26px;}
        </style>
    </head>
    <body>
        <!-- 主页面开始 -->
        <div id="main_body" class="main_body">
            <div class="login_center">
                <div class="begin_form">
                    <form class="submit_form" id="begin_form" method="post" action="{{ URL('Admin/Answer/start') }}">
                        <ul class="login_list">
                            <li>
                                当前车间：<?php echo $data['name']; ?>
                            </li>
                            <li>
                                请选择答题职工:
                                <select name="uid" class="uid">
                                    <?php
                                        foreach ($userinfo as $key => $value) {
                                            echo "<option value='$value[uid]'>$value[nickname]</option>";
                                        }
                                    ?>
                                </select>
                            </li>
                            <li>
                                请选择问题类型:
                                <select name="type" class="type">
                                    <?php
                                        foreach ($type_info as $typeid => $typename) {
                                            echo "<option value='$typeid'>$typename</option>";
                                        }
                                    ?>
                                </select>
                            </li>
                            <li>
                                请输入答题数量:
                                <input type="text" name="limit" class="limit" value="10"/>
                            </li>
                            <li>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="chejian" value="{{ $data['cj_id'] }}">
                                <input type="submit" style="font-size:18px;" value="开始答题" class="login_submit bluebtn" />
                                <input type="button" style="font-size:18px; margin-top:10px;" value="查看答题结果" class="login_submit bluebtn" onclick="tolist()" />
                            </li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script>
        function tolist() {
            window.location.href = "{{ URL('Admin/Answer/list') }}";
        }
    </script>
</html>