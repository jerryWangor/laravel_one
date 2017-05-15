<?php namespace JerryLib\Model;
	use Illuminate\Database\Eloquent\Model;
	use DB;
    use JerryLib\System\Init;
    use JerryLib\System\Common;

	/**
	 * 扩展出来的公共model
	 */
	class CommonModel extends Model {

		use Init;

        protected $config; //配置文件
        protected $attr; //数据数组
        public $debug = false; // 是否开启调试模式
        private $db; // 当前实例化对象
        private $sql; //当前sql语句
        private $params; //当前sql参数
        private $result; //当前结果集
        private static $_instance = null; //单例模式实例化标识

		/**
		 * 构造函数
		 */
        public function __construct($db = 'laravel') {
            $this->attr['db'] = $db;
            $this->db = DB::connection($db);
            return $this;
        }

        /**
         * 克隆函数，防止克隆
         */
        protected function __clone() {
            throw new \Exception('对不起,不能克隆!');
        }

        /**
         * 获得表所在的数据库
         * @return mixed
         */
        protected function getDBName() {
            return $this->attr['db'];
        }

        /**
         * 数据验证，预防SQL注入
         * @param $data
         */
        protected function dataValidate($data) {

        }

        /**
         * 数据过滤
         * @param $data
         */
        protected function dataFilter($data, $type) {

        }

        /**
         * sql执行前处理函数
         */
        public function prepare($command, $params = array()){

	        if(func_num_args()!=2 || !is_array($params)){
	            $_args = func_get_args();
	            $command = array_shift($_args);
	            $params = $_args;
	        }
	    	$this->sql = html_entity_decode($command, ENT_QUOTES);
	    	$this->params = $params;
	        // 对$command进行数据过滤
	        if ( $this->debug ) echo $this->sql;
	        $this->debug = false;
	        
	        return $this;
	    }

	    /**
	     *执行sql
	     */
	    public function execute() {
	    	return Common::objectToArray($this->db->select($this->sql, $this->params));
	    }

	    // 取出一个字段
	    public function fetchOne() {
	        $query = $this->execute();
	        if($query) {
	        	return current(current($query));
	        }
	        return array();
	    }

	    // 取出一条结果
	    public function fetchRow() {
	        $query = $this->execute();
	        if($query) {
	        	return current($query);
	        }
	        return array();
	    }

	    public function fetchAll() {
	        $query = $this->execute();
	        if($query) {
	        	return $query;
	        }
	        return array();
	    }

        /**
         * 分页查询
         */
        public function fetchPage($pageindex = 1, $pagesize = 20) {
            $_command = $this->sql;
            $_params = $this->params;
            $count_command = "select count(*) as count_rows from ($_command) as fetch_page_table_alias";
            $rows_count = intval($this->prepare($count_command,$_params)->fetchOne());
            if($rows_count<0) {
                return array(
                        'page_index'=>1,
                        'page_count'=>1,
                        'rows_count'=>$rows_count,
                        'page_size'=>$pagesize,
                        'page_list'=>array(),
                    );
            }
            $page_all = ceil($rows_count / $pagesize);
            $pagesize = intval($pagesize)<=0 ? 20 : intval($pagesize);
            $pageindex = intval($pageindex)<=1 ? 1 :(intval($pageindex)>$page_all ? $page_all : intval($pageindex));
            $limit = (($pageindex-1)*$pagesize).",$pagesize";
            $_command = "select * from ($_command) as fetch_page_limit_table_alias limit $limit";
            $page_list = $this->prepare($_command,$_params)->fetchAll();
            return array(
                        'page_index'=>$pageindex,
                        'page_count'=>$page_all,
                        'rows_count'=>$rows_count,
                        'page_size'=>$pagesize,
                        'page_list'=>$page_list,
                    );
        }

        /**
         * 插入语句
         */
        public function insert_command($table = null, $data = array(), $get_id = false) {
        	if($get_id) {
        		return $this->db->table($table)->insertGetId($data);
        	}
            return $this->db->table($table)->insert($data);
        }

        /**
         * 插入/更新
         */
        public function insert_update($table = null, $datas = array(), $on_duplicate = false){
            $all_key = $all_val = $params =  $on_dup_vals =  array();
            foreach($datas as $key=>$val) {
                $all_key[] = "$key";
                $all_val[] = "?";
                $params[] = $val;
                $on_dup_vals[] = "$key = values($key)";
            }
            $command = "insert into $table (".implode(',',$all_key).") values (".implode(',',$all_val).")";
            if($on_duplicate) {
                $command .= " ON DUPLICATE KEY UPDATE ".implode(',',$on_dup_vals);
            }
            return $this->db->insert($command, $params);
        }

        /**
         * 更新
         */
        public function update_command() {
        	return $this->db->update($this->sql, $this->params);
        }

        /**
         * 删除
         */
        public function delete_command() {
        	return $this->db->delete($this->sql, $this->params);
        }

	}
?>