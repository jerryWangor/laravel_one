<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>错误率</title>
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
            </div>
            <div class="main_button" id="main_button">
                <div class="action_btn floatl"><input type="button" id="back" style="width:75px;height:26px;" class="bluebtn" onclick="history.back()" value="返回"/></div>
            </div>
            <div class="userList">
                <table id="usertable" class="bordered">
                    <thead>
                        <tr>
                            <th><input class="checkbox" type="checkbox"/></th>
                            <th><nobr>题目id</nobr></th>
                            <th><nobr>问题类型</nobr></th>
                            <th><nobr>题目内容</nobr></th>
                            <th><nobr>错误率</nobr></th>
                        </tr>
                    </thead>
                    <tbody class="main">
                        <?php
                            foreach ($value_count as $tm_id => $error_count) {
                                if(!isset($allinfo[$tm_id])) continue;
                                $content = mb_substr($allinfo[$tm_id]['content'], 0, 50);
                                $type = $allinfo[$tm_id]['type'];
                                echo "<tr>";
                                echo "<td><input class='checkbox' type='checkbox'/></td>";
                                echo "<td>$tm_id</td>";
                                echo "<td>".$type_info[$type]."</td>";
                                echo "<td>$content</td>";
                                echo "<td>".(round($error_count/$all_count, 4)*100)."%</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>