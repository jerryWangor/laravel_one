<?php namespace JerryLib\System;
	
	/**
	 * 日志处理类
	 * @date 2016.1.20
	 * @author Jerry
     * 1.这里需要注意的是每次页面结束前尽量手动注销实例化对象
     * 2.在不影响业务性能效率的情况下，建议手动清理一下日志文件，调用clear()函数
	 */
	
	class Log {
		
		const LOG_ROOT_WINPATH = 'D:/webLog/'; //win日志文件根目录
		const LOG_ROOT_LINUXPATH = '/data/webLog/'; //linux日志文件根目录，这里要注意一下的是nginx启动用户必须对这个目录有写入的权限
		const LOG_SWITCH = true; //日志开关
		const LOG_MAX_LEN = 100000000; //每个日子文件最大的字节数
		const LOG_FILE_PRE = 'log_'; //日志文件前缀,如log_0
        const CLEAR_MAXTIME = 7776000; //最大清理日志时间，单位为秒
		
		private static $_instance = NULL; //单例模式实例化标识
		private static $_logname = NULL; //文件名类型
		private static $_handle = NULL; //文件句柄
		private $log_root_path = NULL; //日志文件根目录
		private $log_file_path = NULL; //日志文件目录
		private $log_max_len = NULL; //日志文件最大长度，超出长度重新建立文件
		private $log_file_pre = NULL; //文件前缀
		
		/**
		 * 构造函数
		 */
		private function __construct() {
			// 根据操作系统类型选择保存日志的目录，如果是linux系统的话需要对日志目录进行赋权
			switch(PHP_OS) {
				case 'WINNT':
				case 'WIN32':
				case 'Windows':
					$this->log_root_path = self::LOG_ROOT_WINPATH;
					break;
				case 'Linux':
				case 'Unix':
					$this->log_root_path = self::LOG_ROOT_LINUXPATH;
					break;
				default:
					$this->log_root_path = self::LOG_ROOT_WINPATH;
					break;
			}
			$this->log_switch = self::LOG_SWITCH;
			$this->log_max_len = self::LOG_MAX_LEN;
		}
		
		/**
		 * 析构函数
		 */
		public function __destruct() {
			if(!is_null(self::$_handle)) {
				$this->close();
			}
		}
		
		/**
		 * 克隆函数，防止克隆
		 */
        private function __clone() {
            throw new \Exception('对不起,不能克隆!');
        }
		
		/**
		 * 外部调用实例化对象
		 */
		public static function getInstance($className = __CLASS__) {
//            if(!(self::$_instance instanceof self)) {
//                self::$_instance = new self;
//            }
//            return self::$_instance;
			if(!isset(self::$_instance) || is_null(self::$_instance))
				return self::$_instance = new $className();
			else
				return self::$_instance;
		}
		
		/**
		 * 写入日志
		 * @params string message: 0 日志描述
		 * @params string logname: 0 日志文件名
		 * @params string logtype: 日志类型
		 * @params int type: 0 普通日志，1 错误日志
		 */
		public function log($message = '', $logname = 'common', $logtype = 'Common', $type = 0) {
			// 如果开启了日志
			if($this->log_switch) {
				$dtime = date('Y-m-d H:i:s');
				if(self::$_logname != $logname || self::$_handle == NULL) {
					self::$_logname = $logname;
					$filename = $this->get_filename($logname);
					self::$_handle = fopen($this->log_file_path . $filename, 'a+');
				}
				switch($type){
					case 0:
						$string = "\r\n" . 'Type: Thing Log' . "\r\n" . 'Time: ' . $dtime . "\r\n" . 'Logtype: ' . $logtype . "\r\n" . 'Message: ' . $message . "\r\n";
						break;
					case 1:
						$string = "\r\n" . 'Type: Error Log' . "\r\n" . 'Time: ' . $dtime . "\r\n" . 'Logtype: ' . $logtype . "\r\n" . 'Message: ' . $message . "\r\n";
						break;
					default:
						$string = "\r\n" . 'Type: Thing Log' . "\r\n" . 'Time: ' . $dtime . "\r\n" . 'Logtype: ' . $logtype . "\r\n" . 'Message: ' . $message . "\r\n";
						break;
				}
				fwrite(self::$_handle, $string);
			}
		}
		
		/**
		 * 返回当前日志文件名
		 * @params string logname: 日志名字
		 */
		private function get_filename($logname = '') {
			if(!empty($logname)) {
				$logname .= '_';
			}
			$this->log_file_pre = self::LOG_FILE_PRE . $logname;
			$log_file_suf = 0;
			// 如果跟目录不存在就创建目录
			if(!file_exists($this->log_root_path)) {
				mkdir($this->log_root_path, 0777);
			}
			// 创建日期文件夹
			$date = date('Y-m-d', time());
			$this->log_file_path = $this->log_root_path . $date . '/';
			if(!file_exists($this->log_file_path)) {
				mkdir($this->log_file_path, 0777);
			}
			
			// 打开一个文件
			if($dh = opendir($this->log_file_path)) {
				// 循环读取文件名
				while(($file = readdir($dh)) !== FALSE) {
					if($file == '.' || $file == '..') {
						continue;
					}
					if(filetype($this->log_file_path . $file) == 'file'){
						$i = substr_count($this->log_file_pre, '_');
						if(strstr($file, $this->log_file_pre)) {
							$count = explode('_', $file);
							$log_file_suf = empty($count[$i]) ? 0 : $count[$i];
						}
					}
				}
				closedir($dh);
				//截断文件
				if(file_exists($this->log_file_path . $this->log_file_pre . $log_file_suf) && filesize($this->log_file_path . $this->log_file_pre . $log_file_suf) >= $this->log_max_len) {
					$log_file_suf = intval($log_file_suf) + 1;
				} else {
					$log_file_suf = intval($log_file_suf);
				}
				return $this->log_file_pre . $log_file_suf . '.txt';
			}
		}
		
		/**
		 * 关闭文件句柄
		 */
		public function close(){
			fclose(self::$_handle);
		}
		
		/**
		 * 删除3个月之前的日志
		 */
		public function clear() {
			if(is_dir($this->log_root_path)) {
				if($dhroot = opendir($this->log_root_path)) {
					while(false !== ($dirfile = readdir($dhroot)))  {
						if($dirfile == '.' || $dirfile == '..') {
							continue;
						}
						if(is_dir($this->log_root_path.$dirfile.'/') && filemtime($this->log_root_path.$dirfile.'/')+self::CLEAR_MAXTIME<time()) {
							if($dhboom = opendir($this->log_root_path.$dirfile.'/')) {
								while(false !== ($logfile = readdir($dhboom)))  {
									if($logfile == '.' || $logfile == '..') {
										continue;
									}
									unlink($this->log_root_path.$dirfile.'/'.$logfile);
								}
								closedir($dhboom);
								rmdir($this->log_root_path.$dirfile);
							}
						}
					}
					closedir($dhroot);
				}
			}
		}
	}
	
	/**
	 * @log params 4:日志类型，用于记录日志描述 必须
	 * @log params 1:文件类型，用于文件名 可选
	 * @log params 2:日志类型，用于记录日志 可选
	 * @log params 3:日志种类，用于标记普通日志和错误日志 可选
	 */
	// Log::getInstance()->log('qqgame', 'qqgame', 'qqgameCommon', 0);