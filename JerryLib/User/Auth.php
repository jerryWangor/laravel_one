<?php namespace JerryLib\User;

    use JerryLib\System\Common;
    use DB;
    use Illuminate\Support\Facades\Session;
	/**
	 * 基于laravel的Auth权限类
	 * date 2016-03-18
	 * author Jerry
	 */
	
	/* 数据库

	*/

	class Auth {

		// 默认配置
		static protected $_config = array(
			'AUTH_ON'           	=> true,                // 认证开关
			'AUTH_ISMODULE'         => false,                // 是否启用模块组
			'AUTH_ISUSERRULE'       => false,                // 是否启用用户单独权限验证
			'AUTH_TYPE'         	=> 2,                   // 认证方式，1为实时认证；2为登录认证。
			'AUTH_USERGROUP'        => 'p_usergroup',        	// 用户组数据表名
			'AUTH_RULE'         	=> 'p_rules',         		// 权限规则表
			'AUTH_USER'         	=> 'p_users',             	// 用户信息表
			'AUTH_MOUDLEGROUP'      => 'p_moduleGroup'             	// 用户信息表
		);

		/**
		 * 检查权限
		 * @param name string|array  需要验证的规则列表,支持逗号分隔的权限规则或索引数组
		 * @param uid  int           认证用户的id
		 * @return boolean           通过验证返回true;失败返回false
         */
        static public function checkrule($rulename, $uid, $ruletype, $isAdmin) {
			if(!self::$_config['AUTH_ON']) return true;
			$authList = self::getAuthList($uid, $ruletype, $isAdmin, true); //获取用户需要验证的所有有效规则列表
			if(is_string($rulename)) {
				if (strpos($rulename, ',') !== false) {
                    $rulename = explode(',', $rulename);
				}
			}
            if(array_key_exists($rulename, $authList)) {
                return true;
            } else {
                return false;
            }

		}

		/**
		 * 获取用户的权限数组
		 * @param $uid int 用户id
		 * @param $ruletype int 验证方式
		 * @param $returnid boolean 是否返回用户的权限id组
		 * @param $isAdmin boolean 是否是管理员
		 * @param $showbtn boolean 是否需要不生成按钮的权限
		 * @return array
		 */
        static private function getGroupRules($uid, $ruletype, $returnid, $isAdmin, $showbtn) {
			static $_groupsRules = array();
            if($returnid === false) {
                if(isset($_groupsRules[$uid]))
                    return $_groupsRules[$uid];
            }
            if($showbtn === true) { //设置要显示的action按钮
                $show_btn = '0,1';
            } else {
                $show_btn = '1';
            }
            // 如果是管理员就查询所有权限
            if($isAdmin === true) {
                $rules_arr = DB::select('select id from '.self::$_config['AUTH_RULE']." where ruletype=$ruletype and status=1");
                $rules_arr = Common::objectToArray($rules_arr);
                foreach($rules_arr as $key=>$value) {
                    if($key == 0) {
                        $RulesIds = $value['id'];
                    } else {
                        $RulesIds .= ','.$value['id'];
                    }
                }
            } else {
                $user_groups = DB::select("select rules from ".self::$_config['AUTH_USER']." a inner join ".self::$_config['AUTH_USERGROUP']." g on a.groupids=g.id where a.uid=$uid and g.status=1;");
                $user_groups = Common::objectToArray($user_groups);
                $RulesIds = $user_groups ? $user_groups[0]['rules'] : array();
            }
            //用户单独权限验证
//            if(self::$_config['AUTH_ISUSERRULE'] === true) {
//                $userRuleIds = DB::select("select groupids from ".self::$_config['AUTH_USER']." where uid=$uid and status=1;");
//                $userRuleIds = Common::objectToArray($userRuleIds);
//                $allRuleIds = trim($RulesIds, ',') . ',' . trim($userRuleIds, ',');
//                $RuleArr = explode(',', $allRuleIds);
//                $RuleArr = array_unique($RuleArr);
//                $RulesIds = implode(',', $RuleArr);
//            }

			if($returnid == true) {
				return $RulesIds;
			}

            $_groupsRules[$uid] = DB::select("select rulename,remark from ".self::$_config['AUTH_RULE']." where ruletype=$ruletype and status=1 and id in ($RulesIds) and showbtn in (".$show_btn.") order by pid,sort asc;");
            $_groupsRules[$uid] = Common::objectToArray($_groupsRules[$uid]);
			return $_groupsRules[$uid];
		}

		/**
		 * 获得权限列表
		 * @param integer $uid  用户id
		 * @param integer $ruletype 验证类型
		 * @param boolean $isAdmin 是否为管理员
		 * @param boolean $showbtn 是否要显示aciton按钮
         * @return array
         *
		 */
        static private function getAuthList($uid, $ruletype, $isAdmin, $showbtn) {
			// 读取用户所属用户组的权限列表
			$rules = self::getGroupRules($uid, $ruletype, false, $isAdmin, $showbtn);
			if(empty($rules)) { // 如果权限为空就返回空数组
				return array();
			}
			// 循环规则，判断结果。
			$authList = array();
			foreach($rules as $rule) {
				$authList[$rule['rulename']] = $rule['remark'];
			}
			return $authList;
		}

		/**
		 * 获取用户权限数组
		 * @param int $uid 认证用户的id
		 * @param int $type 验证方式，0为不需要验证,1为常规验证,2为积分验证
         * @param boolean $isModules 是否分模块组
         * @param $isAdmin boolean 是否管理员权限
		 */
		static public function getUserRules($uid, $ruletype, $isAdmin = false) {
            static $_userRules = array();
            static $_userModule = array();
            $ruleList = self::getAuthList($uid, $ruletype, $isAdmin, false); //获取用户权限列表
            if(self::$_config['AUTH_ISMODULE']) {
                // 如果是分模块组的，就要先获取玩家的模块组信息
                $moduleGroup = self::getUserModules($uid, $ruletype, $isAdmin);
                $i = 1;
                $module_arr = array();
                // 循环构造玩家的权限数组
                foreach($moduleGroup as $m_key=>$m_value) { //这里循环模块组
                    $m_value['id'] = $i;
                    $_userRules[$i] = $m_value;
                    $_userRules[$i]['modules'] = array();
                    $modules = explode(',', $m_value['modules']);
                    foreach($ruleList as $r_key=>$r_value ) { //这里循环权限列表
                        list($module, $controller, $action) = explode('/', $r_key);
                        if(in_array($module, $modules)) {
                            if($controller=='Index' && $action=='index') {
                                $module_arr[$i][$module]['name'] = $r_value;
                                $module_arr[$i][$module]['url'] = $r_key;
                            } elseif ($controller!='Index' && $action=='index') {
                                $module_arr[$i][$module]['controller'][$controller]['name'] = $r_value;
                                $module_arr[$i][$module]['controller'][$controller]['url'] = $r_key;
                            } elseif ($action!='index') {
                                $module_arr[$i][$module]['controller'][$controller]['action'][$action]['name'] = $r_value;
                                $module_arr[$i][$module]['controller'][$controller]['action'][$action]['url'] = $r_key;
                            }
                        }
                        $_userRules[$i]['modules'] = $module_arr[$i];
                    }
                    foreach($module_arr[$i] as $key=>$value) {
                        $_userModule[$key] = $value;
                    }
                    $i++;
                }
                // 设置头部导航栏的SESSION
                Session::put('_AUTH_TOP', $moduleGroup);
            } else {
                // 如果不用分模块组就直接返回权限数组，模块/控制器/操作
                foreach($ruleList as $r_key=>$r_value ) {
                    list($module, $controller, $action) = explode('/', $r_key);
                    if($controller=='Index' && $action=='index') {
                        $module_arr[$module]['name'] = $r_value;
                        $module_arr[$module]['url'] = $r_key;
                    } elseif ($controller!='Index' && $action=='index') {
                        $module_arr[$module]['controller'][$controller]['name'] = $r_value;
                        $module_arr[$module]['controller'][$controller]['url'] = $r_key;
                    } elseif ($action!='index') {
                        $module_arr[$module]['controller'][$controller]['action'][$action]['name'] = $r_value;
                        $module_arr[$module]['controller'][$controller]['action'][$action]['url'] = $r_key;
                    }
                }
//                echo '<pre>';var_dump($module_arr);exit;
                $_userRules = $module_arr;
                $_userModule = $module_arr;
            }
            
            if(self::$_config['AUTH_TYPE']==2) {
                // 规则列表结果保存到session
                Session::put('_AUTH_SIDE', $_userRules);
                Session::put('_AUTH_MOUDLES', $_userModule);
            }
			return $_userRules;
		}

		/**
		 * 获得用户模块组
         * @params int $uid 用户id
         * @params int $ruletype 验证方式
         * @return array
         * [0]=> array(4) { ["name"]=> string(12) "运维管理" ["module_id"]=> string(13) "module_yunwei" ["modules"]=> string(11) "Server,Plat" ["id"]=> int(1) }
         * [1]=> array(4) { ["name"]=> string(12) "运营管理" ["module_id"]=> string(14) "module_yunying" ["modules"]=> string(11) "Shuju,Fenxi" ["id"]=> int(2) }
		 */
		static public function getUserModules($uid, $ruletype, $isAdmin) {
			static $_userModules = array();
			if(!isset($_userModules[$uid])) {
                // 获取ruleid
				$groups = self::getGroupRules($uid, $ruletype, true, $isAdmin, false);
				if(empty($groups)) { // 如果权限为空就返回空数组
					return array();
				}
				$user_modules = DB::select("select distinct substring_index(rulename,'/',1) module from ".self::$_config['AUTH_RULE']." where ruletype=1 and status=1 and id in ($groups);");
                $user_modules = Common::objectToArray($user_modules);
				$i = 0;
                $sql = '';
				foreach($user_modules as $value) {
					if($i>0) {
						$sql .= " or modules like '%{$value['module']}%'";
					} else {
						$sql = "select name,module_id,modules from ".self::$_config['AUTH_MOUDLEGROUP']." where modules like '%{$value['module']}%'";
					}
					$i++;
				}
				$moduleGroups = Common::objectToArray(DB::select($sql));
				$i = 1;
				foreach($moduleGroups as $value) {
					$value['id'] = $i;
					$_userModules[$uid][] = $value;
					$i++;
				}
			}
			return $_userModules[$uid];
		}

        /**
         * 检查用户是否可用
         * @params int $username 用户名
         * @return array or boolean
         */
        static public function getUserInfo($username = '') {
            $userInfo = DB::select('select * from '.self::$_config['AUTH_USER'].' where account=\''.$username.'\' and status>0;');
            if(!$userInfo) {
                return false; //如果查询为空就说明用户不存在或者被封号
            }
            return Common::objectToArray($userInfo[0]);
        }

	}
