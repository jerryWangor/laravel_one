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
                    <label>请输入昵称</label>
                    <input name="nickname" id="nickname" value="<?php echo isset($_GET['nickname']) ? $_GET['nickname'] : '';?>"/>
                    <input type="submit" class="bluebtn btn" name="submit" value="提交">
                </form>
            </div>
            <div class="main_button" id="main_button">
                <div class="action_btn floatl"><b class="btn_icon btn_icon_add"></b><a id="add" href="{{ URL('Admin/User/add') }}">添加用户</a></div>
            </div>
            <div class="userList">
                <table id="usertable" class="bordered">
                    <thead>
                        <tr>
                            <th><input class="checkbox" type="checkbox"/></th>
                            <th><nobr>职工id</nobr></th>
                            <th><nobr>职工账号</nobr></th>
                            <th><nobr>昵称</nobr></th>
                            <th><nobr>性别</nobr></th>
                            <th><nobr>工号</nobr></th>
                            <th><nobr>工种</nobr></th>
                            <th><nobr>入路时间</nobr></th>
                            <th><nobr>出生年月</nobr></th>
                            <th><nobr>状态</nobr></th>
                            <th><nobr>级别</nobr></th>
                            <th><nobr>所属角色组</nobr></th>
                            <th><nobr>所属车间</nobr></th>
                            <th><nobr>创建时间</nobr></th>
                            <th><nobr>操作</nobr></th>
                        </tr>
                    </thead>
                    <tbody class="main">
                        <?php
                            foreach ($data as $key => $value) {

                                // 操作字符串
                                $edit = "<a href='".URL('Admin/User/add?uid='.$value['uid'])."'>编辑</a>";
                                $reset = "<a href='".URL('Admin/User/reset?uid='.$value['uid'])."'>重置密码</a>";
                                $enable_stats = $value['status']==0 ? 1 : 0;
                                $enable_str = $value['status']==0 ? '启用' : '禁用';
                                $enable = "<a href='".URL('Admin/User/enable?uid='.$value['uid'].'&status='.$enable_stats)."'>$enable_str</a>";
                                $delete = "<a href='".URL('Admin/User/delete?uid='.$value['uid'])."'>删除</a>";
                                $caozuo = "<td>$edit | $reset | $enable | $delete</td>";

                                echo "<tr>";
                                echo "<td><input class='checkbox' type='checkbox'/></td>";
                                echo "<td>$value[uid]</td>";
                                echo "<td>$value[account]</td>";
                                echo "<td>$value[nickname]</td>";
                                echo "<td>".(($value['sex'] == 1) ? '男' : '女')."</td>";
                                echo "<td>$value[jobnumber]</td>";
                                echo "<td>$value[jobname]</td>";
                                echo "<td>$value[jobtime]</td>";
                                echo "<td>$value[birthday]</td>";
                                echo "<td>".(($value['status'] == 1) ? '正常' : '已禁用')."</td>";
                                echo "<td>".(($value['level'] == 1) ? '管理员' : '普通用户')."</td>";
                                echo "<td>$value[bname]</td>";
                                echo "<td>$value[cname]</td>";
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