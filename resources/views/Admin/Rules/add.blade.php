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
                    <div class="form-item">
                        <label>请选择父操作:</label>
                        <select name="pid">
                            <option value="0">--最高级--</option>
                            <?php 
                            foreach ($pid_data as $key => $value) {
                                $other = ($value['rulelevel']==2) ? "&nbsp;|--" : '';
                                $selected = (isset($data['pid']) && $data['pid'] == $value['id']) ? 'selected' : '';
                                echo "<option value='$value[id]' $selected>$other$value[remark]</option>";
                            }
                            ?>
                        </select>
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>权限地址：</label>
                        <input type="text" data-required name="rulename" id="rulename" value="<?php echo isset($data['rulename']) ? $data['rulename'] : ''; ?>" />
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>权限名：</label>
                        <input type="text" data-required name="remark" id="remark" value="<?php echo isset($data['remark']) ? $data['remark'] : ''; ?>" />
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>级别：</label>
                        <select name="rulelevel">
                            <option value="3" <?php echo (isset($data['rulelevel']) && $data['rulelevel']==3) ? 'selected' : ''; ?>>操作</option>
                            <option value="2" <?php echo (isset($data['rulelevel']) && $data['rulelevel']==2) ? 'selected' : ''; ?>>控制器</option>
                            <option value="1" <?php echo (isset($data['rulelevel']) && $data['rulelevel']==1) ? 'selected' : ''; ?>>模块</option>
                        </select>
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>排序：</label>
                        <input type="text" name="sort" data-required id="sort" value="<?php echo isset($data['sort']) ? $data['sort'] : 9; ?>">
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>是否显示：</label>
                        <input type="radio" id="showbtn" name="showbtn" value=1 <?php echo (isset($data['showbtn']) && $data['showbtn']==1) || !isset($data['showbtn']) ? 'checked' : ''; ?>>显示
                        <input type="radio" id="showbtn" name="showbtn" value=0 <?php echo (isset($data['showbtn']) && $data['showbtn']==0) ? 'checked' : ''; ?>>不显示
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
                    </div>
                    <div class="form-item">
                        <label>状态：</label>
                        <input type="radio" id="status" name="status" value=1 <?php echo (isset($data['status']) && $data['status']==1) || !isset($data['status']) ? 'checked' : ''; ?>>启用
                        <input type="radio" id="status" name="status" value=0 <?php echo (isset($data['status']) && $data['status']==0) ? 'checked' : ''; ?>>禁用
                        <td class="error_tips colorblack"><span class="icontips floatl"></span><span class="content"></span></td>
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