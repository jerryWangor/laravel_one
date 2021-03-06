<?php 
	namespace App\Http\Controllers\Admin;

    use Illuminate\Support\Facades\Session;
	use JerryLib\Model\CommonModel;
	use JerryLib\System\Common;
    use JerryLib\User\Auth;
    use Request;

    class UserController extends CommonController {

        /**
         * 用户列表
         */
        public function getIndex() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['nickname'])) {
                $wheres[] = "nickname like '%{$this->input['nickname']}%'";
            }

            // 除了管理员其他只能看到自己车间的信息
            if(!$this->is_admin) {
                $wheres[] = "creater=?";
                $params[] = Session::get('_AUTH_USER_NICKNAME');
            }
            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select a.*,b.name as bname,c.name as cname from p_users as a left join p_usergroup as b on a.groupids=b.id left join chejian as c on a.cj_id=c.id $where";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);

            $page_str = Common::page_str($data, $this->naction);

            return view($this->naction, [ 'data'=>$data['page_list'], 'page_str'=>$page_str]);
        }

        /**
         * 新增用户
         */
        public function anyAdd() {

            // 插入/修改用户
            $data = array();
            if(isset($this->input['uid'])) {

                $id = intval($this->input['uid']);
                $sql = "select * from p_users where uid=?";
                $data = $this->common_model->prepare($sql, $id)->fetchRow();                

                // 每个车间账号只能录入和修改自己 录入的用户信息， 管理员可以修改所有的
                if(!Session::get('_AUTH_USER_ISADMIN') && $data['creater']!=Session::get('_AUTH_USER_NICKNAME')) {
                    $this->redirect('', '没有权限修改此用户');
                }
            }

            if(isset($this->input['submit'])) {

                if(!Common::checkUser($this->input['account'])) {
                    $this->redirect('', '用户名格式错误');
                }
                if(isset($this->input['password']) && !Common::checkPwd($this->input['password'])) {
                    $this->redirect('', '密码格式错误');
                }
                // if(!(Common::checkUser($this->input['nickname'], 'CN'))) {
                //     $this->redirect('', '昵称格式错误');
                // }

                $insert_arr = [
                    'account'  =>  $this->input['account'],
                    'nickname'  =>  $this->input['nickname'],
                    'sex'  =>  $this->input['sex'],
                    'idcard'  =>  $this->input['idcard'],
                    'jobnumber'  =>  $this->input['jobnumber'],
                    'jobname'  =>  $this->input['jobname'],
                    'jobtime'  =>  $this->input['jobtime'],
                    'birthday'  =>  $this->input['birthday'],
                    'status'  =>  $this->input['status'],
                    // 'level'  =>  (isset($this->input['uid']) && Session::get('_AUTH_USER_ISADMIN')) ? 1 : 2,
                    'groupids'  =>  isset($this->input['groupids']) ? $this->input['groupids'] : 999,
                    'cj_id'  =>  isset($this->input['cj_id']) ? $this->input['cj_id'] : Session::get('_AUTH_USER_CJID'),
                    'creater'  =>  Session::get('_AUTH_USER_NICKNAME'),
                    'createtime'  =>  date('Y-m-d H:i:s'),
                ];

                // 新增的时候有密码
                if(isset($this->input['password'])) {
                    $insert_arr['password'] = Common::password($this->input['account'], $this->input['password']);
                }

                // 编辑的时候没有密码
                if(isset($this->input['uid'])) {
                    $result = $this->common_model->insert_update('p_users', $insert_arr, true);
                } else {
                    // $insert_arr['password'] = Common::password($this->input['account'], $this->input['account'] . date('Y'));
                    //验证当前用户名是否唯一
                    $check_account = $this->common_model->prepare("select * from p_users where account=?", $this->input['account'])->fetchRow();
                    if(count($check_account)>0) {
                        $this->redirect('', '用户已存在！');
                    }

                    $result = $this->common_model->insert_command('p_users', $insert_arr, true);
                }
                if($result) {
                    $this->redirect('Admin/User/index', '用户插入/更新成功！');
                } else {
                    $this->redirect('', '用户插入/更新失败！');
                }
            }

            // 查询用户组
            $group_data = $this->common_model->prepare("select id,name from p_usergroup where status=1")->fetchAll();
            // 查询车间
            $chejian_data = $this->common_model->prepare("select id,name from chejian")->fetchAll();

            return $this->render(['group_data'=>$group_data, 'chejian_data'=>$chejian_data, 'is_admin'=>$this->is_admin, 'data'=>$data]);
        }

        /**
         * 重置密码
         */
        public function getReset() {

            $sql = "select account from p_users where uid=?";

            $account = $this->common_model->prepare($sql, $this->input['uid'])->fetchOne();

            $reset_pass = $account . date('Y');

            $password = Common::password($account, $reset_pass);

            $sql = "update p_users set password=? where uid=?";

            $result = $this->common_model->prepare($sql, $password, $this->input['uid'])->update_command();

            if($result) {
                $this->redirect('Admin/User/index', '用户'.$account.'重置密码成功！');
            } else {
                $this->redirect('Admin/User/index', '用户'.$account.'重置密码失败！');
            }

        }

        /**
         * 禁用/启用用户
         */
        public function getEnable() {

            $str = $this->input['status']==0 ? '禁用' : '启用';
            $sql = "update p_users set status=? where uid=?";

            $result = $this->common_model->prepare($sql, $this->input['status'], $this->input['uid'])->update_command();

            if($result) {
                $this->redirect('Admin/User/index', '用户'.$str.'成功！');
            } else {
                $this->redirect('Admin/User/index', '用户'.$str.'失败！');
            }

        }

        /**
         * 删除用户
         */
        public function getDelete() {
            if(isset($this->input['uid'])) {
                $uid = intval($this->input['uid']);

                $sql = "delete from p_users where uid=?";

                $result = $this->common_model->prepare($sql, $uid)->delete_command();

                if($result) {
                    $this->redirect('Admin/User/index', '用户删除成功！');
                } else {
                    $this->redirect('', '用户删除失败！');
                }
            }
        }

        /**
         * 修改密码
         */
        public function anyChangepwd() {            
            if(isset($this->input['account'])) {
                $userinfo = Auth::getUserInfo($this->input['account']);
                // 判断旧密码是否正确
                if($userinfo['password'] != Common::password($this->input['account'], $this->input['old_password'])) {
                    $this->redirect('Admin/User/changepwd', '旧密码错误');
                }
                // 判断新密码
                if(!Common::checkPwd($this->input['new_password'])) {
                    $this->redirect('Admin/User/changepwd', '新密码格式不正确');
                }

                $old_password = Common::password($this->input['account'], $this->input['old_password']);
                $new_password = Common::password($this->input['account'], $this->input['new_password']);

                $sql = "update p_users set password=? where account=? and password=?";

                $result = $this->common_model->prepare($sql, $new_password, $this->input['account'], $old_password)->update_command();
                if($result) {
                    $this->redirect('Admin/User/changepwd', '密码修改完成');
                } else {
                    $this->redirect('Admin/User/changepwd', '密码修改失败');
                }

            } else {
                $account = Session::get('_AUTH_USER_ACCOUNT');
                $uid = $this->uid;
                return $this->render(['account'=>$account, 'uid'=>$uid]);
            }
        }

	}
?>