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
                    <label>请输入名字</label>
                    <input name="name" id="name" value="<?php echo isset($_GET['name']) ? $_GET['name'] : '';?>"/>
                    <input type="submit" class="bluebtn btn" name="submit" value="提交">
                </form>
            </div>
            <div class="main_button" id="main_button">
                <div class="action_btn floatl"><b class="btn_icon btn_icon_add"></b><a id="add" href="{{ URL('Admin/UserGroup/add') }}">添加用户组</a></div>
            </div>
            <div class="userList">
                <table id="usertable" class="bordered">
                    <thead>
                        <tr>
                            <th><input class="checkbox" type="checkbox"/></th>
                            <th><nobr>用户组id</nobr></th>
                            <th><nobr>用户组名称</nobr></th>
                            <th><nobr>状态</nobr></th>
                            <th><nobr>权限列表</nobr></th>
                            <th><nobr>创建时间</nobr></th>
                            <th><nobr>操作</nobr></th>
                        </tr>
                    </thead>
                    <tbody class="main">
                        <?php
                            foreach ($data as $key => $value) {

                                // 操作字符串
                                $edit = "<a href='".URL('Admin/UserGroup/add?id='.$value['id'])."'>编辑</a>";
                                $enable_stats = $value['status']==0 ? 1 : 0;
                                $enable_str = $value['status']==0 ? '启用' : '禁用';
                                $enable = "<a href='".URL('Admin/UserGroup/enable?id='.$value['id'].'&status='.$enable_stats)."'>$enable_str</a>";
                                $delete = "<a href='".URL('Admin/UserGroup/delete?id='.$value['id'])."'>删除</a>";
                                $caozuo = "<td>$edit | $enable | $delete</td>";

                                echo "<tr>";
                                echo "<td><input class='checkbox' type='checkbox'/></td>";
                                echo "<td>$value[id]</td>";
                                echo "<td>$value[name]</td>";
                                echo "<td>".(($value['status'] == 1) ? '正常' : '已禁用')."</td>";
                                echo "<td>$value[rules]</td>";
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