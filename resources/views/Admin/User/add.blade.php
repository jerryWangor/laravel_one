<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>添加权限</title>
        <!-- 引入标签库 -->
        <tagLib name="html" />
        <!-- 加载头部公共文件 -->
        <?php require_once(HEADER_URL); ?>
        <style>
            .form-item { margin: 20px 0 ;}
            .form-item span {color:#03A9F4; font-size: 12px;}
            .form-item label { display: inline-block; width: 100px; text-align: right;}
        </style>
    </head>
    <body>
        <!-- 主页面开始 -->
        <div id="user_add" class="user_add">
            <form id="user_form" action="" method="post">
                <div class="one-content">
                    <?php if(isset($is_admin) && $is_admin===true) { ?>
                    <div class="form-item">
                        <label>请选择角色组:</label>
                        <select name="groupids">
                            <?php 
                            foreach ($group_data as $key => $value) {
                                $selected = (isset($data['groupids']) && $data['groupids'] == $value['id']) ? 'selected' : '';
                                echo "<option value='$value[id]' $selected>$value[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-item">
                        <label>请选择车间:</label>
                        <select name="cj_id">
                            <?php 
                            foreach ($chejian_data as $key => $value) {
                                $selected = (isset($data['cj_id']) && $data['cj_id'] == $value['id']) ? 'selected' : '';
                                echo "<option value='$value[id]' $selected>$value[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                    <div class="form-item">
                        <label>用户名：</label>
                        <input type="text" data-required name="account" id="account" value="<?php echo isset($data['account']) ? $data['account'] : ''; ?>" />
                    </div>
                    <?php if(!isset($data['password'])) { ?>
                    <div class="form-item">
                        <label>密码：</label>
                        <input type="password" data-required name="password" id="password" value="<?php echo isset($data['password']) ? $data['password'] : ''; ?>" />
                    </div>
                    <?php } ?>
                    <div class="form-item">
                        <label>中文名：</label>
                        <input type="text" data-required name="nickname" id="nickname" value="<?php echo isset($data['nickname']) ? $data['nickname'] : ''; ?>" />
                    </div>
                    <div class="form-item">
                        <label>性别：</label>
                        <input type="radio" id="sex" name="sex" value=1 <?php echo (isset($data['sex']) && $data['sex']==1) || !isset($data['sex']) ? 'checked' : ''; ?>>男
                        <input type="radio" id="sex" name="sex" value=0 <?php echo (isset($data['sex']) && $data['sex']==2) ? 'checked' : ''; ?>>女
                    </div>
                    <div class="form-item">
                        <label>身份证号：</label>
                        <input type="text" data-required name="idcard" id="idcard" value="<?php echo isset($data['idcard']) ? $data['idcard'] : ''; ?>" />
                    </div>
                    <div class="form-item">
                        <label>工号：</label>
                        <input type="text" data-required name="jobnumber" id="jobnumber" value="<?php echo isset($data['jobnumber']) ? $data['jobnumber'] : ''; ?>" />
                    </div>
                    <div class="form-item">
                        <label>工种：</label>
                        <input type="text" data-required name="jobname" id="jobname" value="<?php echo isset($data['jobname']) ? $data['jobname'] : ''; ?>" />
                    </div>
                    <div class="form-item">
                        <label>入路时间：</label>
                        <input type="text" data-required name="jobtime" id="jobtime" value="<?php echo isset($data['jobtime']) ? $data['jobtime'] : ''; ?>" />
                    </div>
                    <div class="form-item">
                        <label>出生年月：</label>
                        <input type="text" data-required name="birthday" id="birthday" value="<?php echo isset($data['birthday']) ? $data['birthday'] : ''; ?>" />
                    </div>
                    <div class="form-item">
                        <label>状态：</label>
                        <input type="radio" id="status" name="status" value=1 <?php echo (isset($data['status']) && $data['status']==1) || !isset($data['status']) ? 'checked' : ''; ?>>启用
                        <input type="radio" id="status" name="status" value=0 <?php echo (isset($data['status']) && $data['status']==0) ? 'checked' : ''; ?>>禁用
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
        <script type="text/javascript">
            $('#jobtime').datetimepicker({
                lang:"ch",           //语言选择中文
                format:"Y-m-d",      //格式化日期
                timepicker:false    //关闭时间选项
            });
            $('#birthday').datetimepicker({
                lang:"ch",           //语言选择中文
                format:"Y-m-d",      //格式化日期
                timepicker:false    //关闭时间选项
            });
        </script>
    </body>
</html>