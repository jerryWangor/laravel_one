<?php namespace JerryLib\Session;
    
    class MySessionHandler implements SessionHandlerInterface {

        /**
        * @access private
        * @var object 数据库连接
        */
        private $_dbLink;
        /**
        * @access private
        * @var string 保存session的表名
        */
        Private $_sessionTable = 'p_session';
        /**
        * @access private
        * @var string session名
        */
        private $_sessionName;
        /**
        * @const 过期时间
        */
        const SESSION_EXPIRE = 180;

        // 构造函数
        public function __construct($dblink='', $sessionTable='') {
            $this->_sessionTable = !empty($sessionTable)?$sessionTable:$this->_sessionTable; 
            if(!empty($dblink) && is_object($dblink)) {
                $this->_dbLink = $dblink;
            } else {
                require_once('mysqli.class.php');
                $dbh = DB::getInstance();
                $dbh->selectDB($this->_sessionTable);
                $this->_dbLink = $dbh->conn;
            }
            session_set_save_handler(
                array(&$this, 'open'),    // 在运行session_start()时执行
                array(&$this, 'close'),   // 在脚本执行完成 或 调用session_write_close() 或 session_destroy()时被执行，即在所有session操作完后被执行
                array(&$this, 'read'),    // 在运行session_start()时执行，因为在session_start时，会去read当前session数据
                array(&$this, 'write'),   // 此方法在脚本结束和使用session_write_close()强制提交SESSION数据时执行
                array(&$this, 'destroy'), // 在运行 session_destroy()时执行
                array(&$this, 'gc')       // 执行概率由session.gc_probability 和 session.gc_divisor的值决定，时机是在open，read之后，session_start会相继执行open，read和gc
            );
        }

        public function __clone() {

        }

        public function __destruct() {
            unset($dbh);
        }

        /**
        * 打开
        * @access public
        * @param string $session_save_path 保存session的路径
        * @param string $session_name session名
        * @return integer
        */
        public function open($session_save_path, $session_name) {
            $this->_sessionName = $session_name;
            return 1;
        }

        /**
        * 关闭
        * @access public
        * @return integer
        */
        public function close() {
            $this->_dbLink->close();
        }

        /**
        * 读取session
        * @access public
        * @param string $session_id session ID
        * @return string
        */
        public function read($session_id) {
            // $sql = "SELECT value FROM {$this->_sessionTable} WHERE sid = '{$session_id}' AND UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE.' > UNIX_TIMESTAMP(NOW())';
            $sql = "SELECT value FROM {$this->_sessionTable} WHERE sid = '{$session_id}'";
            $result = $this->_dbLink->query($sql);
            if($result) {
                if(mysqli_num_rows($result)>0) {
                    $resultt = $this->_dbLink->query("UPDATE {$this->_sessionTable} SET expiration = CURRENT_TIMESTAMP() WHERE sid = '{$session_id}'");
                    if(list($value) = mysqli_fetch_row($result)){  
                        return $value; 
                    }
                }   
            }
            return 0;
        }

        /**
        * 写入session
        * @access public
        * @param string $session_id session ID
        * @param string $session_data session data
        * @return integer
        */
        public function write($session_id, $session_data) {
            $sql = "SELECT value FROM {$this->_sessionTable} WHERE sid = '{$session_id}' AND UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE.' > UNIX_TIMESTAMP(NOW())';
            $result = $this->_dbLink->query($sql);
            if($result) {
                if(mysqli_num_rows($result)>0) {
                    $result = $this->_dbLink->query("UPDATE {$this->_sessionTable} SET value = '{$session_data}' WHERE sid = '{$session_id}'");
                    if($result) {
                        return 1;
                    }
                } else {
                    $result = $this->_dbLink->query("INSERT INTO {$this->_sessionTable} (sid, value, expiration) VALUES ('{$session_id}', '{$session_data}', CURRENT_TIMESTAMP())");
                    if($result) {
                        return 1;
                    }
                }
            }
            return 0;  
        }

        /**
        * 销毁session
        * @access public
        * @param string $session_id session ID
        * @return integer
        */
        public function destroy($session_id) {
            $result = $this->_dbLink->query("DELETE FROM {$this->_sessionTable} WHERE UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE." < UNIX_TIMESTAMP(NOW())");
            if($result) {
                return 1;
            }
            return 0;
        }

        /**
        * 垃圾回收
        * @access public
        * @param string $maxlifetime session 最长生存时间
        * session.gc_maxlifetime
        * session.gc_probability   
        * session.gc_divisor 
        * session.gc_divisor 与 session.gc_probability 合起来定义了在每个会话初始化时启动 gc（garbage collection 垃圾回收）进程的概率。
        * 此概率用 gc_probability/gc_divisor 计算得来。例如 1/100 意味着在每个请求中有 1% 的概率启动 gc 进程。
        * @return integer
        */
        public function gc($maxlifetime) {
            $result = $this->_dbLink->query("DELETE FROM {$this->_sessionTable} WHERE UNIX_TIMESTAMP(expiration) + ".self::SESSION_EXPIRE." < UNIX_TIMESTAMP(NOW())");
            if($result) {
                return 1;
            }
            return 0;
        }
    }
?>