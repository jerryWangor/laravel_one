<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>添加用户组</title>
        <!-- 引入标签库 -->
        <tagLib name="html" />
        <!-- 加载头部公共文件 -->
        <?php require_once(HEADER_URL); ?>
        <style>
            .form-item { margin: 20px 0 ;}
            .form-item span {color:#03A9F4; font-size: 12px;}
            .form-item label { display: inline-block; width: 100px; text-align: right;}
            .fp_rules { width: 100%; margin-left: 100px;}
            .rules { margin: 10px 50px; }
            .parent_li { display: block; height: 50px;}
            ul .child { margin: 10px 30px; }
            ul .child li { float:left; margin-left: 20px;}
        </style>
    </head>
    <body>
        <!-- 主页面开始 -->
        <div id="user_add" class="user_add">
            <form id="user_form" action="" method="post">
                <div class="one-content">
                    <div class="form-item">
                        <label>用户组名：</label>
                        <input type="text" data-required name="name" id="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>状态：</label>
                        <input type="radio" id="status" name="status" value=1 <?php echo (isset($data['status']) && $data['status']==1) || !isset($data['status']) ? 'checked' : ''; ?>>启用
                        <input type="radio" id="status" name="status" value=0 <?php echo (isset($data['status']) && $data['status']==0) ? 'selected' : ''; ?>>禁用
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>分配权限：</label>
                        <div class="fp_rules">
                            <?php
                                foreach ($m_group_data as $key => $value) {
                                    $checked = in_array($value['id'], $has_rule) ? 'checked' : '';
                                    echo "<input class='pparent_rule' name='rules[]' type='checkbox' value='$value[id]' $checked/>$value[remark]";
                                }
                            ?>
                            <ul class="rules">
                                <?php
                                    foreach ($c_group_data as $key => $value) {
                                        echo "<li class='parent_li'>";
                                        $checked = in_array($value['id'], $has_rule) ? 'checked' : '';
                                        echo "<input class='parent_rule' name='rules[]' type='checkbox' value='$value[id]' $checked/>$value[remark]";
                                        if(isset($a_group_data[$value['id']])) {
                                            echo "<ul class='child'>";
                                            foreach ($a_group_data[$value['id']] as $k => $v) {
                                                $checked = in_array($v['id'], $has_rule) ? 'checked' : '';
                                                echo "<li><input class='child_rule' name='rules[]' type='checkbox' value='$v[id]' $checked/>$v[remark]</li>";
                                            }
                                            echo "</ul>";
                                        }
                                        echo "</li>";
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="form-item">
                    <label></label>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="submit" id="submit" name="submit" value="提交" class="btn btnLg green2 mt30">
                    <input type="reset" id="reset" name="reset" value="重置" class="btn btnLg blue2 mt30">
                </div>
            </form>
        </div>
    </body>
</html>