<?php 
	namespace App\Http\Controllers\Admin;

    use Illuminate\Support\Facades\Session;
	use JerryLib\Model\CommonModel;
	use JerryLib\System\Common;
    use Request;

    class UserGroupController extends CommonController {

        /**
         * 用户组列表
         */
        public function getIndex() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['name'])) {
                $wheres[] = "name like '%{$this->input['name']}%'";
                // $params[] = $this->input['name'];
            }

            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select * from p_usergroup $where";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);

            $page_str = Common::page_str($data, $this->naction);

            return view($this->naction, [ 'data'=>$data['page_list'], 'page_str'=>$page_str]);
        }

        /**
         * 新增用户组
         */
        public function anyAdd() {

            // 插入/修改用户
            $data = $has_rule = array();
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "select * from p_usergroup where id=?";

                $data = $this->common_model->prepare($sql, $id)->fetchRow(); 

                $has_rule = explode(',', $data['rules']);
            }

            if(isset($this->input['submit'])) {

                $insert_arr = [
                    'name'  =>  $this->input['name'],
                    'status'  =>  $this->input['status'],
                    'rules'  =>  implode(',', $this->input['rules']),
                    'createtime'  =>  date('Y-m-d H:i:s'),
                ];

                if(isset($this->input['id'])) {
                    $insert_arr['id'] = $this->input['id'];
                    $result = $this->common_model->insert_update('p_usergroup', $insert_arr, true);
                } else {

                    $result = $this->common_model->insert_command('p_usergroup', $insert_arr, true);
                }
                if($result) {
                    $this->redirect('Admin/UserGroup/index', '用户组插入/更新成功！');
                } else {
                    $this->redirect('', '用户组插入/更新失败！');
                }
            }

            // 查询用户组
            $m_group_data = $this->common_model->prepare("select id,remark,rulename,pid from p_rules where status=1 and rulelevel=1")->fetchAll();
            $c_group_data = $this->common_model->prepare("select id,remark,rulename,pid from p_rules where status=1 and rulelevel=2")->fetchAll();
            $tmp_group_data = $this->common_model->prepare("select id,remark,rulename,pid from p_rules where status=1 and rulelevel=3")->fetchAll();

            $a_group_data = array();
            foreach ($tmp_group_data as $key => $value) {
                $a_group_data[$value['pid']][] = $value;
            }
            
            return $this->render(['c_group_data'=>$c_group_data, 'm_group_data'=>$m_group_data, 'a_group_data'=>$a_group_data, 'data'=>$data, 'has_rule'=>$has_rule]);
        }

        /**
         * 禁用/启用用户组
         */
        public function getEnable() {

            $str = $this->input['status']==0 ? '禁用' : '启用';
            $sql = "update p_usergroup set status=? where id=?";

            $result = $this->common_model->prepare($sql, $this->input['status'], $this->input['id'])->update_command();

            if($result) {
                $this->redirect('Admin/UserGroup/index', '用户'.$str.'成功！');
            } else {
                $this->redirect('Admin/UserGroup/index', '用户'.$str.'失败！');
            }

        }

        /**
         * 删除用户组
         */
        public function getDelete() {
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "delete from p_usergroup where id=?";

                $result = $this->common_model->prepare($sql, $id)->delete_command();

                if($result) {
                    $this->redirect('Admin/UserGroup/index', '用户组删除成功！');
                } else {
                    $this->redirect('', '用户组删除失败！');
                }
            }
        }

	}
?>