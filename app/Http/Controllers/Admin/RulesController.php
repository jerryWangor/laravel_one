<?php 
	namespace App\Http\Controllers\Admin;

	use JerryLib\Model\CommonModel;
    use JerryLib\System\Common;
    use Request;

    class RulesController extends CommonController {

        /**
         * 权限列表
         */
        public function getIndex() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['remark'])) {
                $wheres[] = "remark like '%{$this->input['remark']}%'";
                // $params[] = $this->input['remark'];
            }

            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select * from p_rules $where";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);
            
            $page_str = Common::page_str($data, $this->naction);

            return $this->render(['data'=>$data['page_list'], 'page_str'=>$page_str]);
        }

        /**
         * 新增权限
         */
        public function anyAdd() {

            // 插入/修改权限
            $data = array();
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "select * from p_rules where id=?";

                $data = $this->common_model->prepare($sql, $id)->fetchRow();                
            }

            if(isset($this->input['submit'])) {
                $insert_arr = [
                    'rulename'  =>  $this->input['rulename'],
                    'remark'  =>  $this->input['remark'],
                    'status'  =>  $this->input['status'],
                    'rulelevel'  =>  $this->input['rulelevel'],
                    'sort'  =>  $this->input['sort'],
                    'showbtn'  =>  $this->input['showbtn'],
                    'pid'  =>  $this->input['pid'],
                    'createtime'  =>  date('Y-m-d H:i:s'),
                ];

                if(isset($this->input['id'])) {
                    $result = $this->common_model->insert_update('p_rules', $insert_arr, true);
                } else {
                    $result = $this->common_model->insert_command('p_rules', $insert_arr, true);
                }
                if($result) {
                    $this->redirect('Admin/Rules/index', '权限插入/更新成功！');
                } else {
                    $this->redirect('', '权限插入/更新失败！');
                }
            }

            // 查询父操作
            $pid_data = $this->common_model->prepare("select id,remark,rulelevel from p_rules where rulelevel<3")->fetchAll();

            return $this->render(['pid_data'=>$pid_data, 'data'=>$data]);
        }

        /**
         * 禁用/启用权限
         */
        public function getEnable() {

            $str = $this->input['status']==0 ? '禁用' : '启用';
            $sql = "update p_rules set status=? where id=?";

            $result = $this->common_model->prepare($sql, $this->input['status'], $this->input['id'])->update_command();

            if($result) {
                $this->redirect('Admin/Rules/index', '权限'.$str.'成功！');
            } else {
                $this->redirect('Admin/Rules/index', '权限'.$str.'失败！');
            }

        }

        /**
         * 删除权限
         */
        public function getDelete() {
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "delete from p_rules where id=?";

                $result = $this->common_model->prepare($sql, $id)->delete_command();

                if($result) {
                    $this->redirect('Admin/Rules/index', '权限删除成功！');
                } else {
                    $this->redirect('', '权限删除失败！');
                }
            }
        }
        
	}
?>