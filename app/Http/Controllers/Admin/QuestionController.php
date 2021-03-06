<?php 
	namespace App\Http\Controllers\Admin;

    use Illuminate\Support\Facades\Session;
	use JerryLib\Model\CommonModel;
	use JerryLib\System\Common;
    use Request;

    class QuestionController extends CommonController {

        /**
         * 问题列表
         */
        public function getIndex() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['type'])) {
                $wheres[] = "type={$this->input['type']}";
            }

            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select * from question $where";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);

            $page_str = Common::page_str($data, $this->naction);

            // 查询问题类型
            $temp_data = $this->common_model->prepare("select * from question_type")->fetchAll();
            $type_info = array();
            foreach ($temp_data as $key => $value) {
                $type_info[$value['id']] = $value['name'];
            }

            return view($this->naction, [ 'data'=>$data['page_list'], 'page_str'=>$page_str, 'type_info'=>$type_info ]);
        }

        /**
         * 新增问题
         */
        public function anyAdd() {

            // 插入/修改用户
            $data = array();
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "select * from question where id=?";

                $data = $this->common_model->prepare($sql, $id)->fetchRow();                
            }

            if(isset($this->input['submit'])) {

                $insert_arr = [
                    'type'  =>  $this->input['type'],
                    'content'  =>  $this->input['content'],
                    'choseA'  =>  $this->input['choseA'],
                    'choseB'  =>  $this->input['choseB'],
                    'choseC'  =>  $this->input['choseC'],
                    'choseD'  =>  $this->input['choseD'],
                    'true_answer'  =>  $this->input['true_answer'],
                    'creater'  =>  Session::get('_AUTH_USER_NICKNAME'),
                    'createtime'  =>  date('Y-m-d H:i:s'),
                ];

                if(isset($this->input['id'])) {
                    $insert_arr['id'] = $this->input['id'];
                    $result = $this->common_model->insert_update('question', $insert_arr, true);
                } else {
                    $result = $this->common_model->insert_command('question', $insert_arr, true);
                }
                if($result) {
                    $this->redirect('Admin/Question/index', '问题插入/更新成功！');
                } else {
                    $this->redirect('', '问题插入/更新失败！');
                }
            }

            // 查询问题类型
            $temp_data = $this->common_model->prepare("select * from question_type")->fetchAll();
            $type_info = array();
            foreach ($temp_data as $key => $value) {
                $type_info[$value['id']] = $value['name'];
            }

            return $this->render(['data'=>$data, 'type_info'=>$type_info ]);
        }

        /**
         * 删除问题
         */
        public function getDelete() {
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "delete from question where id=?";

                $result = $this->common_model->prepare($sql, $id)->delete_command();

                if($result) {
                    $this->redirect('Admin/Question/index', '问题删除成功！');
                } else {
                    $this->redirect('', '问题删除失败！');
                }
            }
        }

        /**
         * 问题类型列表
         */
        public function getType() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['name'])) {
                $wheres[] = "name like '%{$this->input['name']}%'";
            }

            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select * from question_type $where";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);

            $page_str = Common::page_str($data, $this->naction);

            return view($this->naction, [ 'data'=>$data['page_list'], 'page_str'=>$page_str ]);
        }

        /**
         * 新增问题类型
         */
        public function anyAddtype() {

            // 插入/修改用户
            // 插入/修改用户
            $data = array();
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "select * from question_type where id=?";

                $data = $this->common_model->prepare($sql, $id)->fetchRow();                
            }


            if(isset($this->input['submit'])) {

                $insert_arr = [
                    'name'  => $this->input['name'],
                ];

                if(isset($this->input['id'])) {
                    $insert_arr['id'] = $this->input['id'];
                    $result = $this->common_model->insert_update('question_type', $insert_arr, true);
                } else {
                    $result = $this->common_model->insert_command('question_type', $insert_arr, true);
                }

                if($result) {
                    $this->redirect('Admin/Question/type', '问题类型插入/更新成功！');
                } else {
                    $this->redirect('', '问题类型插入/更新失败！');
                }
            }

            return $this->render(['data'=>$data]);
        }

        /**
         * 删除问题类型
         */
        public function getDeletetype() {
            if(isset($this->input['id'])) {
                $id = intval($this->input['id']);

                $sql = "delete from question_type where id=?";

                $result = $this->common_model->prepare($sql, $id)->delete_command();

                if($result) {
                    $this->redirect('Admin/Question/type', '问题类型删除成功！');
                } else {
                    $this->redirect('', '问题类型删除失败！');
                }
            }
        }

	}
?>