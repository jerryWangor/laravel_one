<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>答题页面</title>
        <!-- 引入标签库 -->
        <tagLib name="html" />
        <!-- 加载头部公共文件 -->
        <?php require_once(HEADER_URL); ?>
        <style>
        .start_list { font-size: 16px; margin-top: 10px; }
        .question { margin-top: 10px; }
        .question span { margin-left: 30px; margin-top: 5px; display: block; }
        .main_body {
            width: 800px; position: absolute; left: 50%; margin-left: -400px;
        }
        .main_body ul li { margin-top: 5px; }
        .login_submit { width: 150px; }
        .chose { width:15px; height:15px; }
        </style>
    </head>
    <body>
        <!-- 主页面开始 -->
        <div id="main_body" class="main_body">
            <form class="submit_form" id="start_form" method="post" action="{{ URL('Admin/Answer/start') }}" onsubmit="return checkform()">
                <ul class="start_list">
                    <li>
                        当前车间：{{ $chejian['name'] }}
                    </li>
                    <li>
                        当前答题职工: {{ $userinfo['nickname'] }}
                    </li>
                    <div class="question" id="question1">
                        <li>------开始答题------</li>
                    <?php
                        foreach ($data as $key => $value) {
                            $tihao = intval($key)+1;
                            echo "<p>{$tihao}、{$value['content']}</p>";
                            echo "<span>A.{$value['choseA']}</span>";
                            echo "<span>B.{$value['choseB']}</span>";
                            echo "<span>C.{$value['choseC']}</span>";
                            echo "<span>D.{$value['choseD']}</span>";
                            echo "<span>正确答案：{$value['true_answer']}</span>";
                            echo "<span>";
                            echo "<input type='radio' name='chose[{$value['id']}]' class='chose' value='1'>正确
                                  <input type='radio' name='chose[{$value['id']}]' class='chose' value='2'>错误";
                            echo "</span>";
                        }

                    ?>
                    </div>
                    <li>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="uid" value="{{ $uid }}">
                        <input type="hidden" name="limit" value="{{ $limit }}">
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="chejian" value="{{ $chejian['id'] }}">
                        <input type="submit" name="submit" style="font-size:18px;" value="提交" class="login_submit bluebtn">
                    </li>
                </ul>
            </form>
        </div>
    </body>
    <script>
        // 检查每道题是否选择答案
        function checkform() {
            var limit = "<?php echo $limit; ?>";
            var v = document.getElementsByClassName('chose');
            console.log(v.length);
            var j = 0;
            for (var i=0;i<v.length;i++) {
                if(v.item(i).checked) {
                    j++;
                }
            }
            if(j==0) {
                alert("请先选择答案！！！");
                console.log(" 一个位选中");
                return false;
            } else if(j<limit) {
                var other = limit-j;
                alert("还有"+other+"道题目没有选择答案！！！");
                console.log("选中了"+j+"个");
                return false;
            }
            return true;      
        }
    </script>
</html>