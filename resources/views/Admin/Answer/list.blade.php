<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>主页面</title>
        <!-- 引入标签库 -->
        <tagLib name="html" />
        <!-- 加载头部公共文件 -->
        <?php require_once(HEADER_URL); ?>
        <script type="text/javascript">
            dotable();
        </script>
    </head>
    <body>
        <!-- 主页面开始 -->
        <div id="main_body" class="main_body">
            <div class="main_title">
                <form name="search_form" class="search_form" action="" method="get">
                    <label>请输入职工</label>
                    <input name="nickname" id="nickname" valu4e="<?php echo isset($_GET['nickname']) ? $_GET['nickname'] : '';?>"/>
                    <label>--</label>
                    <label>请输入车间</label>
                    <input name="chejian" id="chejian" valu4e="<?php echo isset($_GET['chejian']) ? $_GET['chejian'] : '';?>"/>
                    <input type="submit" class="bluebtn btn" name="submit" value="提交">
                </form>
            </div>
            <div class="main_button" id="main_button">
                <!-- <div class="action_btn floatl"><b class="btn_icon btn_icon_manageuser"></b><a id="add" href="{{ URL('Admin/Answer/rank') }}">车间分数排行</a></div> -->
                <div class="action_btn floatl"><b class="btn_icon btn_icon_manageuser"></b><a id="add" href="{{ URL('Admin/Answer/error') }}">每道题的错误率</a></div>
            </div>
            <div class="userList">
                <table id="usertable" class="bordered">
                    <thead>
                        <tr>
                            <th><input class="checkbox" type="checkbox"/></th>
                            <th><nobr>职工id</nobr></th>
                            <th><nobr>所属车间</nobr></th>
                            <th><nobr>问题类型</nobr></th>
                            <th><nobr>答对题目</nobr></th>
                            <th><nobr>答错题目</nobr></th>
                            <th><nobr>答题时间</nobr></th>
                        </tr>
                    </thead>
                    <tbody class="main">
                        <?php
                            foreach ($data as $key => $value) {
                                echo "<tr>";
                                echo "<td><input class='checkbox' type='checkbox'/></td>";
                                echo "<td>$value[nickname]</td>";
                                echo "<td>$value[name]</td>";
                                echo "<td>".$type_info[$value['type']]."</td>";
                                echo "<td>$value[true_ids]</td>";
                                echo "<td>$value[error_ids]</td>";
                                echo "<td>$value[createtime]</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <ul class="pager" id="pager"><?php echo $page_str; ?></ul>
            </div>
        </div>
    </body>
</html>