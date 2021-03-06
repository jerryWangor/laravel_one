<?php 
	namespace App\Http\Controllers\Admin;

    use Illuminate\Support\Facades\Session;
	use JerryLib\Model\CommonModel;
	use JerryLib\System\Common;
    use Request;

    class AnswerController extends CommonController {

        /**
         * 用户列表
         */
        public function getIndex() {

            // 读取当前用户的车间
            $sql = "select a.cj_id,b.name from (select cj_id from p_users where uid=?) as a left join chejian as b on a.cj_id=b.id";

            $data = $this->common_model->prepare($sql, $this->uid)->fetchRow();

            if($data) {
                // 查询该车间下所有的职工
                $sql = "select uid,nickname from p_users where cj_id=?";
                $userinfo = $this->common_model->prepare($sql, $data['cj_id'])->fetchAll();
            }

            // 查询问题类型
            $temp_data = $this->common_model->prepare("select * from question_type")->fetchAll();
            $type_info = array();
            foreach ($temp_data as $key => $value) {
                $type_info[$value['id']] = $value['name'];
            }

            return view($this->naction, [ 'data'=>$data, 'userinfo'=>$userinfo, 'type_info'=>$type_info ]);
        }

        /**
         * 开始答题
         */
        public function anyStart() {
            // 当前答题的车间和职工
            // stop($this->input);
            $chejian = $this->input['chejian'];
            $uid = $this->input['uid'];
            $type = $this->input['type'];
            $limit = $this->input['limit'];

            // 如果是提交答题的话
            if(isset($this->input['submit'])) {
                // 判断每道题的答案，记录日志
                $true_arr = $error_arr = array();
                foreach ($this->input['chose'] as $q_id => $value) {
                    if($value==1) {
                        $true_arr[] = $q_id;
                    } else {
                        $error_arr[] = $q_id;
                    }
                }

                $insert_arr = [
                    'chejian'  =>  $this->input['chejian'],
                    'uid'  =>  $this->input['uid'],
                    'type'  =>  $this->input['type'],
                    'true_ids'  =>  implode(',', $true_arr),
                    'error_ids'  =>  implode(',', $error_arr),
                    'createtime'  =>  date('Y-m-d H:i:s'),
                ];

                $result = $this->common_model->insert_command('answer_log', $insert_arr, true);
                if($result) {
                    $this->redirect('Admin/Answer/list', '答题完成！');
                } else {
                    $this->redirect('Admin/Answer/index', '答题失败！');
                }
            }

            // 从题库中随机抽取limit道题,注意类型是type的
            $data_ids = $this->common_model->prepare("select id from question where type=?", $type)->fetchAll();
            $ids = array();
            foreach ($data_ids as $key => $value) {
                $ids[$value['id']] = $value['id'];
            }
            if(count($ids)<$limit) {
                $this->redirect('Admin/Answer/index', "对不起，当前类型的题库没有{$limit}道题！");
            }
            // 取十个随机的id
            $new_ids = array_rand($ids, $limit);
            $ids_str = implode(',', $new_ids);

            $sql = "select * from question where id in ($ids_str) and type=?";

            $data = $this->common_model->prepare($sql, $type)->fetchAll();

            $sql = "select uid,nickname from p_users where uid=?";
            $userinfo = $this->common_model->prepare($sql, $uid)->fetchRow();

            $sql = "select id,name from chejian where id=?";
            $chejian = $this->common_model->prepare($sql, $chejian)->fetchRow();

            return view($this->naction, [ 'data'=>$data, 'chejian'=>$chejian, 'uid'=>$uid, 'type'=>$type, 'limit'=>$limit, 'userinfo'=>$userinfo ]);
        }

        /**
         * 查看答题结果
         */
        public function anyList() {

            $page = isset($this->input['page']) ? $this->input['page'] : 1;
            // 查询用户列表
            $wheres = $params = array();
            if(isset($this->input['nickname'])) {
                $wheres[] = "b.nickname like '%{$this->input['nickname']}%'";
            }
            if(isset($this->input['chejian'])) {
                $wheres[] = "c.name like '%{$this->input['chejian']}%'";
            }
            $where = count($wheres)>0 ? ' where ' . implode(' and ', $wheres) : '';

            $sql = "select a.*,b.nickname,c.name from answer_log as a left join p_users as b on a.uid=b.uid left join chejian as c on a.chejian=c.id $where order by a.id desc";
            
            $data = $this->common_model->prepare($sql, $params)->fetchPage($page, 20);

            $page_str = Common::page_str($data, $this->naction);

            // 查询问题类型
            $sql = "select id,name from question_type";
            $temp_data = $this->common_model->prepare($sql)->fetchAll();
            $type_info = array();
            foreach ($temp_data as $key => $value) {
                $type_info[$value['id']] = $value['name'];
            }

            return view($this->naction, [ 'data'=>$data['page_list'], 'page_str'=>$page_str, 'type_info'=>$type_info ]);
        }

        /**
         * 每道题的错误率
         */
        public function anyError() {

            // 查询所有错误的题
            $sql = "select error_ids from answer_log where error_ids<>''";

            $temp_data = $this->common_model->prepare($sql)->fetchAll();

            $error_arr = array();
            foreach ($temp_data as $key => $value) {
                $error_arr[] = $value['error_ids'];
            }

            $error_str = implode(',', $error_arr);

            $all_arr = explode(',', $error_str);

            $all_count = count($all_arr);

            $value_count = array_count_values($all_arr);
            arsort($value_count);

            // 查询所有题的信息
            $sql = "select id,type,content from question";

            $temp_data = $this->common_model->prepare($sql)->fetchAll();
            $allinfo = array();
            foreach ($temp_data as $key => $value) {
                $allinfo[$value['id']] = $value;
            }

            // 查询问题类型
            $sql = "select id,name from question_type";
            $temp_data = $this->common_model->prepare($sql)->fetchAll();
            $type_info = array();
            foreach ($temp_data as $key => $value) {
                $type_info[$value['id']] = $value['name'];
            }

            return view($this->naction, [ 'all_count'=>$all_count, 'value_count'=>$value_count, 'allinfo'=>$allinfo, 'type_info'=>$type_info ]);
        }
    }
?>