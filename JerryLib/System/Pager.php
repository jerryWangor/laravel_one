<?php namespace JerryLib\System;

    /**
     * 分页类
     * Created by Jerry
     * Date:    2015-04-24
     * example: $pager = new Pager(100, 10);
     *          $pager->showPager();
     */
    class Pager {

        private $rowSum;    // 总记录数
        private $row;       // 每页记录数
        private $pageSum;   // 总页数
        private $page;		// 当前页
        private $func;      // 点击执行函数
        private $lang;		// 语言
        private $url;		// URL地址
        private $cn = array(
            'records' => '条记录',
            'pages'   => '页',
            'first'   => '首页',
            'pre' 	  => '上一页',
            'next'    => '下一页',
            'last'    => '尾页'
        );
        private $en = array(
            'records' => 'Records',
            'pages'   => 'Pages',
            'first'   => 'First',
            'pre'     => 'Pre',
            'next'    => 'Next',
            'last'    => 'Last'
        );

        /**
         * 构造函数
         */
        function __construct($rowSum, $page = 1, $func = '', $row = 20, $lang = 'cn', $url = '') {
            $this->rowSum = $rowSum;
            $row = ($row < 1) ? (1) : ($row);
            $this->row = $row;
            $this->func = $func;
            $this->pageSum = ceil($rowSum / $row); //计算总共有多少页pageSum
            $this->page = $page; //获取当前页
            $this->lang = $this->$lang;
        }

        /**
         * 分页sql
         */
        public function limit() {
            $start = ($this->page - 1) * $this->row;
            $row = $this->row;
            return " LIMIT $start, $row";
        }


        /**
         * 显示分页HTML
         * @param int $show_tongji 显示统计信息 0关闭 1开启
         * @param int $show_numindex 显示数字索引 0关闭 1开启
         * @param int $show_centernumss 数字索引中间数量 最好为奇数 其实就是每页显示的个数
         * @param int $show_input 显示跳转框 0关闭 1开启
         * @param int $show_select 显示下拉框 0关闭 1开启
         * @param int $show_selects 下拉框显示数量 最好为偶数
         */
        public function showPager($show_tongji = 1, $show_pagenum = 5, $show_numindex = 1, $show_centernums = 10, $show_input = 0, $show_select = 0, $show_selects = 10) {
            $rowSum = $this->rowSum;
            $row = $this->row;
            $pageSum = $this->pageSum;
            $page = min($pageSum, $this->page);
            $lang = $this->lang;
            $func = $this->func;
            $html = '';

            // 统计信息
            if($show_tongji) {
                $start = ($rowSum) ? (($page - 1) * $row + 1) : (0);  //当前页开始的条数，比如第4页，以31开始
                $end = min($page * $row, $rowSum);  //当前页结束的条数，比如第4页，以40结束
                $html .= "<span>共{$rowSum}{$lang['records']}</span><span>{$page}/{$pageSum}{$lang['pages']}</span>";
            }
            if($pageSum <= 1)  //如果只有一页数据，就返回
                return $html;

            // 首页$prev上一页
            $prev = $page - 1;
            $html .= ($prev) ? ('<a href="#"' . " onclick='$func" . '(' . ($page-1) . ')' .  "'>" . $lang['pre'] . '</a>') : '';

            // 分页数字索引
            if($show_numindex) {
                $num_lf = ceil($show_centernums / 2);  //获取左右的个数
                $num_area = $page - $num_lf;  //获取当前页面减去5
                $num_bu = $pageSum - $page;  //判断是否在左边补足10个
                if($num_area < 0) { $num_area = 0; }
                if($pageSum < 1) { $pageSum = 1; }
                for($i=1;$i<=$show_centernums;$i++) {
                    if($num_bu < $num_lf && $pageSum >= 10) {  //如果点到倒数几个页面
                        $num = $num_area + $i + $num_bu - $num_lf;  //这里的$num就是从需要补充的开始
                    } else {
                        if($page > 5 && $page < 10) {  //判断如果总行数小于10，就显示全部
                            $num = $i;
                        } else {
                            $num = $num_area + $i; //如果不需要补充，还是按照数字索引左边第四个开始
                        }
                    }
                    if($num <= 0) break;
                    if ($num == $pageSum + 1) break;
                    $html .= ($num == $page) ? ('<span class="current">' . $page . '</span>') : ("<a href='#' onclick='$func" . '(' . $num . ')' . "'>{$num}</a>");
                    if ($i > $pageSum) break;
                }
            }

            // 下一页、尾页
            $next = ($page == $pageSum) ? (0) : ($page + 1);
            $html .= ($next) ? ("<a href='#' onclick='$func" . '(' . ($page+1) . ')' . "'>{$lang['next']}</a>") : '';

            // 输入页码跳转
            if($show_input) {
                $html .= "<span>转到<input type='text' class='to-page' size='3' maxlength='3' title='输入页码后回车' onkeyup=\"this.value=this.value.replace(/\D/g,'')\" onafterpaste = \"this.value = this.value.replace(/\D/g,'')\" onkeydown = \"javascript:if(event.charCode==13||event.keyCode==13){if(!isNaN(this.value)){$func}return false;}\"/>{$lang['pages']}</span>";
            }

            // 下拉框选页码跳转
            if($show_select) {
                $html .= "<span>转至<select name='topage' onchange='$func'>\n";
                $lvtao = $page - $show_selects / 2;
                $lvtaos = ($lvtao <= 0) ? (1) : ($lvtao);
                $lvtaoe = $page + $show_selects / 2;
                if ($page < $show_selects / 2 && $show_selects <= $pageSum) {
                    $lvtaoe = $show_selects;
                } else if ($lvtaoe >= $pageSum) {
                    $lvtaoe = $pageSum;
                }
                for ($i = $lvtaos; $i <= $lvtaoe; $i++) {
                    $html .= ($i == $page) ? ("<option value='$i' selected>$i</option>\n") : ("<option value='$i'>$i</option>\n");
                }
                $html .= "</select>{$lang['pages']}</span>";
            }
            return $html;
        }
    }
