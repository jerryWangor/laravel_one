<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>添加问题</title>
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
                        <label>请选择问题类型:</label>
                        <select name="type">
                            <?php
                                foreach ($type_info as $typeid => $typename) {
                                    $selected = (isset($data['type']) && $data['type']==$typeid) ? 'selected' : '';
                                    echo "<option value='$typeid' $selected>$typename</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-item">
                        <label>问题内容：</label>
                        <textarea type="text" style="width:400px; height:100px;" name="content" id="content" value=""><?php echo isset($data['content']) ? $data['content'] : ''; ?></textarea>
                    </div>
                    <div class="form-item">
                        <label>答案A：</label>
                        <input type="text" name="choseA" id="choseA" value="<?php echo isset($data['choseA']) ? $data['choseA'] : ''; ?>"/>
                    </div>
                    <div class="form-item">
                        <label>答案B：</label>
                        <input type="text" name="choseB" id="choseB" value="<?php echo isset($data['choseB']) ? $data['choseB'] : ''; ?>"/>
                    </div>
                    <div class="form-item">
                        <label>答案C：</label>
                        <input type="text" name="choseC" id="choseC" value="<?php echo isset($data['choseC']) ? $data['choseC'] : ''; ?>"/>
                    </div>
                    <div class="form-item">
                        <label>答案D：</label>
                        <input type="text" name="choseD" id="choseD" value="<?php echo isset($data['choseD']) ? $data['choseD'] : ''; ?>"/>
                    </div>
                    <div class="form-item">
                        <label>正确答案：</label>
                        <input type="text" name="true_answer" id="true_answer" value="<?php echo isset($data['true_answer']) ? $data['true_answer'] : ''; ?>"/>
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