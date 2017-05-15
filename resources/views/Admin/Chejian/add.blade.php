<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>添加车间</title>
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
                        <label>车间名：</label>
                        <input type="text" data-required name="name" id="name" value="<?php echo isset($data['name']) ? $data['name'] : ''; ?>" />
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