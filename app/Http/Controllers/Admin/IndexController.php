<?php 
	namespace App\Http\Controllers\Admin;
	use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Session;
    use JerryLib\System\Verify;
    use JerryLib\System\Common;
    use JerryLib\User\userManage;
    use Request;
    use Redirect;

    class IndexController extends Controller {

        //后台主页面
		public function getIndex(Request $request) {
            if(!Session::has('_AUTH_USER_ISLOGIN') && !Session::get('_AUTH_USER_ISLOGIN')===true) {
                return Redirect::to('Admin/Index/login');
            }
            // 获取当前在线人数,从session
            $onlineNum = userManage::Init()->getOnlineNum();
            // 获取侧边栏菜单
            $sidedata = Session::get('_AUTH_SIDE');
            // stop($sidedata);
            return view('Admin.Index.index', ['sidedata'=>$sidedata, 'onlineNum'=>$onlineNum]);
		}

        //主区域页面
        public function getMain() {
            if(!Session::has('_AUTH_USER_ISLOGIN') && !Session::get('_AUTH_USER_ISLOGIN')===true) {
                return Redirect::to('Admin/Index/login');
            }
            return view('Admin.Index.main');
        }

        //登录页面
        public function getLogin() {
            if(Session::has('_AUTH_USER_ISLOGIN') && Session::get('_AUTH_USER_ISLOGIN')===true) {
                return Redirect::to('Admin/Index/index');
            }
            $checkVerify = Session::get('checkVerify') ? Session::get('checkVerify') : false;
            return view('Admin.Index.login', ['checkVerify' => $checkVerify]);
        }

        //获取验证码
        public function getVerify() {
            // var_dump('aaaa');exit;
            Verify::doimg();
        }

        //检查登录
        public function postChecklogin(Request $request) {
            $inputs = Common::filter_form_data($request::all());
            if($return = userManage::Init()->checkLogin($inputs)) {
                return response()->json($return);
            }
        }

        //退出
        public function getLogout(Redirect $Redirect) {
            if(!Session::has('_AUTH_USER_ISLOGIN') && !Session::get('_AUTH_USER_ISLOGIN')===true) {
                return Redirect::to('Admin/Index/login');
            }
            //记录退出日志
            userManage::Init()->loginOut(session('_AUTH_USER_UID'));
            //注销session
            Session::flush();
            return $Redirect::to('Admin/Index/login');
        }
	}
?>