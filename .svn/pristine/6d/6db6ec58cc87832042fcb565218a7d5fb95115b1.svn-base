<?php 
	namespace App\Http\Controllers\Admin;
	use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Session;
    use JerryLib\System\Pager;
    use JerryLib\Model\CommonModel;
    use JerryLib\System\Common;
    use Request;

	class CommonController extends Controller {

        protected $action;
        protected $actiondata;
        protected $input;
        protected $common_model;
        /**
         * 公共的构造函数
         */
        public function __construct(Request $request) {
            $this->input = Common::filter_form_data($request::all());
            $url = trim(Request::getRequestUri(), '/');
            if($len = strpos($url, '?')) {
                $this->naction = substr($url, 0, strpos($url, '?'));    
            } else {
                $this->naction = $url;
            }
            $this->common_model = CommonModel::Init();
            $this->is_admin = Session::get('_AUTH_USER_ISADMIN');
            $this->uid = Session::get('_AUTH_USER_UID');
        }

        /**
         * 跳转
         */
        public function render($data = array(), $url = '') {
            $url = $url ? $url : $this->naction;
            return view($url, $data);
        }

        /**
         * 跳转并且弹消息
         */
        public function redirect($url = '', $msg = ""){
            if($url) {
                $url = "window.location.href='" . URL($url) . "'";
            } else {
                $url = "window.history.back()";
            }
            $msg = $msg ? "alert('$msg');" : '';
            exit("<script>$msg;$url;</script>");
        }

	}
?>