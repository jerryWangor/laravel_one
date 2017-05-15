<?php 
	namespace App\Http\Controllers;
	use App\Http\Model\hello;

	class helloController extends Controller {
		protected $model;
		public function __construct(hello $hello) { //这里申明$hello就是hello类的对象,限制参数只能是该类或者该类的子类的实例
			$this->model = $hello;
		}
		public function index() {
			return view('hello', $this->model->index());
		}
	}
?>