<?php 
	namespace App\Http\Controllers\Admin;

    use Illuminate\Support\Facades\Session;
	use JerryLib\Model\CommonModel;
	use JerryLib\System\Common;
    use Request;

    class ChejianController extends CommonController {

        /**
         * 车间列表
         */
        public function getIndex() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['name'])) {
                $wheres[] = "name like '%{$this->input['name']}%'";
            }

            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select * from chejian $where";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);

            $page_str = Common::page_str($data, $this->naction);

            return view($this->naction, [ 'data'=>$data['page_list'], 'page_str'=>$page_str]);
        }

        /**
         * 新增车间
         */
        public function anyAdd() {

            // 插入/修改用户
            $data = $has_rule = array();
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "select * from chejian where id=?";

                $data = $this->common_model->prepare($sql, $id)->fetchRow(); 
            }

            if(isset($this->input['submit'])) {

                $insert_arr = [
                    'name'  =>  $this->input['name'],
                ];

                if(isset($this->input['id'])) {
                    $insert_arr['id'] = $this->input['id'];
                    $result = $this->common_model->insert_update('chejian', $insert_arr, true);
                } else {
                    $result = $this->common_model->insert_command('chejian', $insert_arr, true);
                }
                if($result) {
                    $this->redirect('Admin/Chejian/index', '车间插入/更新成功！');
                } else {
                    $this->redirect('', '车间插入/更新失败！');
                }
            }

            return $this->render(['data'=>$data]);
        }

        /**
         * 删除车间
         */
        public function getDelete() {
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "delete from chejian where id=?";

                $result = $this->common_model->prepare($sql, $id)->delete_command();

                if($result) {
                    $this->redirect('Admin/Chejian/index', '车间删除成功！');
                } else {
                    $this->redirect('', '车间删除失败！');
                }
            }
        }

	}
?>