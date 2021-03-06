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
                    <label>请选择问题类型</label>
                    <select name="type">
                        <?php
                            foreach ($type_info as $typeid => $typename) {
                                $selected = (isset($_GET['type']) && $_GET['type']==$typeid) ? 'selected' : '';
                                echo "<option value='$typeid' $selected>$typename</option>";
                            }
                        ?>
                    </select>
                    <input type="submit" class="bluebtn btn" name="submit" value="提交">
                </form>
            </div>
            <div class="main_button" id="main_button">
                <div class="action_btn floatl"><b class="btn_icon btn_icon_add"></b><a id="add" href="{{ URL('Admin/Question/add') }}">添加问题</a
                    ></div>
                <div class="action_btn floatl"><b class="btn_icon btn_icon_add"></b><a id="add" href="{{ URL('Admin/Question/type') }}">问题类型列表</a
                    ></div>
            </div>
            <div class="userList">
                <table id="usertable" class="bordered">
                    <thead>
                        <tr>
                            <th><input class="checkbox" type="checkbox"/></th>
                            <th><nobr>问题id</nobr></th>
                            <th><nobr>问题类型</nobr></th>
                            <th><nobr>问题内容</nobr></th>
                            <th><nobr>答案A</nobr></th>
                            <th><nobr>答案B</nobr></th>
                            <th><nobr>答案C</nobr></th>
                            <th><nobr>答案D</nobr></th>
                            <th><nobr>正确答案</nobr></th>
                            <th><nobr>创建人</nobr></th>
                            <th><nobr>创建时间</nobr></th>
                            <th><nobr>操作</nobr></th>
                        </tr>
                    </thead>
                    <tbody class="main">
                        <?php
                            foreach ($data as $key => $value) {

                                // 操作字符串
                                $edit = "<a href='".URL('Admin/Question/add?id='.$value['id'])."'>编辑</a>";
                                $delete = "<a href='".URL('Admin/Question/delete?id='.$value['id'])."'>删除</a>";
                                $caozuo = "<td>$edit | $delete</td>";

                                // 处理长字符串
                                $content = mb_substr($value['content'], 0, 10);
                                $choseA = mb_substr($value['choseA'], 0, 5);
                                $choseB = mb_substr($value['choseB'], 0, 5);
                                $choseC = mb_substr($value['choseC'], 0, 5);
                                $choseD = mb_substr($value['choseD'], 0, 5);

                                echo "<tr>";
                                echo "<td><input class='checkbox' type='checkbox'/></td>";
                                echo "<td>$value[id]</td>";
                                echo "<td>".(isset($type_info[$value['type']]) ? $type_info[$value['type']] : 0)."</td>";
                                echo "<td title='$value[content]'>$content</td>";
                                echo "<td title='$value[choseA]'>$choseA</td>";
                                echo "<td title='$value[choseB]'>$choseB</td>";
                                echo "<td title='$value[choseC]'>$choseC</td>";
                                echo "<td title='$value[choseD]'>$choseD</td>";
                                echo "<td>$value[true_answer]</td>";
                                echo "<td>$value[creater]</td>";
                                echo "<td>$value[createtime]</td>";
                                echo $caozuo;
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