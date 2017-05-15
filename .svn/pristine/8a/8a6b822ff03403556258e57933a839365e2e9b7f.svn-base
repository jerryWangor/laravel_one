<?php namespace JerryLib\DB;

/* mysqli DB类：
 * 1、增删查改
 * 2、外部调用getInstance连接数据库
 * 3、config.php为数据库配置文件
 */
class DbHelper {

    private $db_host; //数据库主机
    private $db_user; //数据库用户名
    private $db_pwd; //数据库用户名密码
    private $db_name; //数据库名
    private $db_port; //数据库名
    private $conn; //数据库连接标识;
    private $result; //执行query命令的结果资源标识
    private $row = 0; //返回的条目数
    private static $_sql = ''; //sql执行语句
    private static $_coding = 'utf8'; //数据库编码，GBK,UTF8,gb2312
    private static $_pconn = false; //数据库连接类型（即时连接还是长连接）;
    private static $_saveError = false; //是否开启错误记录
    private static $_showError = true; //测试阶段，显示所有错误,具有安全隐患,默认关闭
    private static $_isError = false; //发现错误是否立即终止,默认false,建议不启用，因为当有问题时用户什么也看不到是很苦恼的
    private static $_instance = null; //单例模式实例化标识

    // 构造函数
    private function __construct($pconn, $coding, $dbhost, $dbuser, $dbpwd, $dbname, $dbport) {
        // 引入配置文件，初始化属性
        $Config = require(__DIR__ . '/../Config/Database.php');
        $this->db_host = empty($dbhost) ? $Config['dbhost'] : $dbhost;
        $this->db_user = empty($dbuser) ? $Config['dbuser'] : $dbuser;
        $this->db_pwd = empty($dbpwd) ? $Config['dbpasswd'] : $dbpwd;
        $this->db_name = empty($dbname) ? $Config['dbname'] : $dbname;
        $this->db_port = empty($dbport) ? $Config['dbport'] : $dbport;
        self::$_pconn = $pconn;
        self::$_coding = empty($coding) ? $Config['charset'] : $coding;
        $this->connect();
    }

    // 析构函数，自动关闭数据库,垃圾回收机制
    public function __destruct() {
        if(!empty($this->result) && is_object($this->result)) {
            $this->result->free();
        }
        $this->conn->close();
    }

    // 连接数据库
    private function connect() {
        if(self::$_pconn) $this->db_host = 'p:' . $this->db_host; // 持久连接
        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_pwd, $this->db_name, $this->db_port);

        // 设置数据库编码
        $this->conn->query('SET NAMES ' . self::$_coding);
        // 测试错误日志
        // if(self::$_showError) {
        // $this->show_error('数据库不可用：', $this->db_name);
        // }
    }

    // 外部调用实例化对象
    public static function getInstance($pconn = false, $coding = 'utf8', $dbhost = '', $dbuser='', $dbpwd = '', $dbname = '', $dbport = '', $className = __CLASS__) {
        if(!isset(self::$_instance) || is_null(self::$_instance))
            return self::$_instance = new $className($coding, $pconn, $dbhost, $dbuser, $dbpwd, $dbname, $dbport);
        else
            return self::$_instance;
    }

    // 执行原生sql，返回结果集
    public function query($sql='') {
        if($sql == '') {
            if(self::$_showError) {
                $this->show_error('错误SQL语句：', self::$_sql);
            }
        }
        self::$_sql = $sql;
        $this->result = $this->conn->query(self::$_sql);
        if(!$this->result) {
            if(self::$_showError) {
                $this->show_error('错误SQL语句：', self::$_sql);
            }
        }
        return $this->result;
    }

    //查询服务器所有数据库，将系统数据库与用户数据库分开，更直观的显示
    public function show_databases() {
        $this->query('show databases;');
        echo '现有的数据库一共有：'.($amount = $this->select_num_rows()).'个';
        echo '<br />';
        $i = 1;
        $rowarray = array();
        while($rowarray = mysqli_fetch_array($this->result)) {
            echo "$i--{$rowarray['Database']}";
            echo '<br />';
            $i++;
        }
    }

    // 以数组形式返回主机中所有数据库名
    public function databases() {
        $this->query('show databases;');
        $data = array();
        $rowarray = array();
        $i = 0;
        while($rowarray = mysqli_fetch_array($this->result, MYSQL_ASSOC)) {
            $data[$i] = $rowarray['Database'];
            $i++;
        }
        return $data;
    }

    // 查询数据库下的所有表
    public function show_tables($dbname = '') {
        $dbname = empty($dbname) ? $this->db_name : $dbname ;
        $this->query("show tables from $dbname;");
        echo '当前数据库'.$dbname.'一共有：'.($amount = $this->select_num_rows()).'个表';
        echo '<br />';
        $i = 1;
        $rowarray = array();
        $columnName = 'Tables_in_'.$dbname;
        while($rowarray = mysqli_fetch_array($this->result)) {
            echo "$i--{$rowarray[$columnName]}";
            echo '<br />';
            $i++;
        }
    }

    /*
     * mysql_fetch_row()    array  $row[0],$row[1],$row[2]
     * mysql_fetch_array()  array  $row[0] 或 $row[id]
     * mysql_fetch_assoc()  array  用$row->content 字段大小写敏感
     * mysql_fetch_object() object 用$row[id],$row[content] 字段大小写敏感
     */

    // 取得记录集,获取数组-索引和关联,使用$row['content']
    public function fetch_array($result = '') {
        if($result != '') {
            return mysqli_fetch_array($result);
        } else {
            return mysqli_fetch_array($this->result);
        }
    }

    // 获取关联数组,使用$row['字段名']
    public function fetch_assoc() {
        return mysqli_fetch_assoc($this->result);
    }

    // 获取数字索引数组,使用$row[0],$row[1],$row[2]
    public function fetch_row() {
        return mysqli_fetch_row($this->result);
    }

    // 获取对象数组,使用$row->content
    public function fetch_object() {
        return mysqli_fetch_object($this->result);
    }

    // 查询所有数据
    public function findall($table) {
        $this->query("SELECT * FROM $table;");
    }

    // 简化查询select，根据传入进来的字段和表名查询
    public function select($table, $columnName = '*', $condition = '', $debug = false) {
        $condition = $condition ? 'Where '.$condition : NULL;
        if($debug) {
            return "SELECT $columnName FROM $table $condition;";
        } else {
            $this->query("SELECT $columnName FROM $table $condition;");
        }
    }

    // 简化删除del
    public function delete($table, $condition, $url = '') {
        if($this->query("DELETE FROM $table WHERE $condition;")) {
            return true;
        } else {
            return false;
        }
    }

    //简化插入insert
    public function insert($table, $columnName, $value, $url = '') {
        if($this->query("INSERT INTO $table ($columnName) VALUES ($value);")) {
            return true;
        } else {
            return false;
        }
    }

    //简化修改update
    public function update($table, $update_content, $condition, $url = '') {
        if ($this->query("UPDATE $table SET $update_content WHERE $condition;")) {
            return true;
        } else {
            return false;
        }
    }

    // 取得上一步 INSERT 操作产生的 ID
    public function insert_id() {
        return mysqli_insert_id($this->conn);
    }

    // 指向确定的一条数据记录
    public function db_data_seek($id) {
        if(!mysqli_data_seek($this->result, $id)) {
            if(self::$_showError) {
                $this->show_error('SQL语句有误：', '指定的数据为空');
            }
        }
    }

    // 根据insert,update,delete执行结果取得影响行数
    public function iud_affected_rows() {
        return mysqli_affected_rows($this->conn);
    }

    // 根据select查询结果计算结果集条数
    public function select_num_rows() {
        if($this->result == null) {
            if(self::$_showError) {
                $this->show_error('查询结果集为空：', self::$_sql);
            }
        } else {
            return mysqli_num_rows($this->result);
        }
    }

    // 获取最后一个执行的sql语句
    public static function getLastSql(){
        return self::$_sql;
    }

    // 获取客户端真实IP地址
    public function get_real_ip() {
        $ip = false;
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            if($ip){
                array_unshift($ips, $ip); $ip = FALSE;
            }
            for($i = 0; $i < count($ips); $i++){
                if (!eregi ('^(10|172\.16|192\.168)\.', $ips[$i])){
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }

    // 输出显示错误日志
    public function show_error($message = '', $sql = '') {
        if(!$sql) {
            echo "<font color='red'>".$message."</font>";
            echo "<br />";
        } else {
            echo "<fieldset>";
            echo "<legend>错误信息提示:</legend><br />";
            echo "<div style='font-size:14px; clear:both; font-family:Verdana, Arial, Helvetica, sans-serif;'>";
            echo "<div style='height:20px; background:#000000; border:1px #000000 solid'>";
            echo "<font color='white'>错误号：12142</font>";
            echo "</div><br />";
            echo "错误原因：".$this->conn->error."<br /><br />";
            echo "<div style='height:20px; background:#FF0000; border:1px #FF0000 solid'>";
            echo "<font color='white'>".$message."</font>";
            echo "</div>";
            echo "<font color='red'><pre>".$sql."</pre></font>";
            $ip = $this->get_real_ip();
            if(self::$_saveError) {
                $time = date('Y-m-d H:i:s');
                $message = $message."\r\n".$sql."\r\n客户IP:".$ip."\r\n时间:".$time."\r\n\r\n";
                $server_date = date('Y-m-d');
                $filename = $server_date.'.txt'; // 日志文件名
                $file_path = 'error/'.$filename; // 日期文件
                $error_content = $message;
                $file = 'error'; // 设置文件保存目录
                // 建立文件夹
                if(!file_exists($file)) {
                    if(!mkdir($file, 0777)) {
                        //默认的 mode 是 0777，意味着最大可能的访问权
                        die('upload files directory does not exist and creation failed');
                    }
                }
                // 建立txt日期文件
                if(!file_exists($file_path)) {
                    fopen($file_path, 'w+');
                    // 首先要确定文件存在并且可写
                    if(is_writable($file_path)) { // 判断是否可写
                        // 使用添加模式打开$filename，文件指针将会在文件的开头
                        if(!$handle = fopen($file_path, 'a')) {
                            echo "不能打开文件 $filename";
                            exit;
                        }
                        // 将$somecontent写入到我们打开的文件中。
                        if(!fwrite($handle, $error_content)) {
                            echo "不能写入到文件 $filename";
                            exit;
                        }
                        echo "文件 $filename 写入成功";
                        echo "——错误记录被保存!";
                        //关闭文件
                        fclose($handle);
                    } else {
                        echo "文件 $filename 不可写";
                    }
                } else {
                    // 首先要确定文件存在并且可写
                    if(is_writable($file_path)) {
                        // 使用添加模式打开$filename，文件指针将会在文件的开头
                        if(!$handle = fopen($file_path, 'a')) {
                            echo "不能打开文件 $filename";
                            exit;
                        }
                        // 将$somecontent写入到我们打开的文件中。
                        if(!fwrite($handle, $error_content)) {
                            echo "不能写入到文件 $filename";
                            exit;
                        }
                        echo "文件 $filename 写入成功";
                        echo "——错误记录被保存!";
                        //关闭文件
                        fclose($handle);
                    } else {
                        echo "文件 $filename 不可写";
                    }
                }
            }
            echo "<br />";
            if(self::$_isError) {
                exit;
            }
        }
        echo "</div>";
        echo "</fieldset>";
        echo "<br />";
    }

    //释放结果集
    public function free() {
        mysqli_free_result($this->result);
    }

    //数据库选择
    public function select_db($db_name) {
        return mysqli_select_db($this->conn, $db_name);
    }

    //查询字段数量
    public function num_fields($table_name) {
        $this->query("select * from $table_name");
        echo "<br />";
        echo "字段数：".($total = mysqli_num_fields($this->result));
        echo "<pre>";
        while($finfo = mysqli_fetch_field($this->result)) {
            printf("Name:     %s\n", $finfo->name);
            printf("Table:    %s\n", $finfo->table);
            printf("max. Len: %d\n", $finfo->max_length);
            printf("Flags:    %d\n", $finfo->flags);
            printf("Type:     %d\n\n", $finfo->type);
        }
        echo "</pre>";
        echo "<br />";
    }

    //取得 MySQL 服务器信息
    public function mysql_server($num = '') {
        switch($num) {
            case 1 :
                return mysqli_get_server_info($this->conn); //MySQL 服务器信息
                break;
            case 2 :
                return mysqli_get_host_info($this->conn); //取得 MySQL 主机信息
                break;
            case 3 :
                return mysqli_get_client_info($this->conn); //取得 MySQL 客户端信息
                break;
            case 4 :
                return mysqli_get_proto_info($this->conn); //取得 MySQL 协议信息
                break;
            default :
                return mysqli_get_client_info($this->conn); //默认取得mysql版本信息
        }
    }

    // 检查是不是一个sql
    public function inject_check($sql_str) {
        $check = preg_match('/^select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/', $sql_str, $array);
        if(!$check) {
            if(self::$_showError) {
                $this->show_error('输入非法注入内容！', $sql_str);
            }
            return false;
        } else {
            return $sql_str;
        }
    }

    // 防止SQL注入
    public function sql_injection($content) {
        if(!get_magic_quotes_gpc()) {
            if(is_array($content)) {
                foreach ($content as $key=>$value) {
                    $content[$key] = addslashes($value);
                }
            } else {
                echo 1;
                $content = addslashes($content);
            }
        }
        return $content;
    }

    // 检查来路
    public function checkurl() {
        if(preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
            header("Location: http://Localhost");
            exit();
        }
        return true;
    }
}

// 测试
// $mysql_conn = DB::getInstance();
// $result = $mysql_conn->select('merry_user', 'account', '', false);
// $result = $mysql_conn->insert('merry_user', 'account,nickname,password,status,create_time', "'yaowenlong','顾其龙','".md5('123')."',1,".time());
// $result = $mysql_conn->update('merry_user', "account='yaowenlong'", "account='guqilong'");
// $result = $mysql_conn->delete('merry_user', "account='yaowenlong'");
// $mysql_conn->db_data_seek(1);
// $data[] = $mysql_conn->fetch_assoc();
// var_dump($data);
// var_dump($mysql_conn->sql_injection("a;sdsa'"));
// var_dump($data);
// while($data = $mysql_conn->fetch_assoc()) {
// var_dump($data);
// }
// unset($mysql_conn);
    