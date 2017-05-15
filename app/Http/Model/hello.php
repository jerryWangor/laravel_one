<?php 
	namespace App\Http\Model;
	use Illuminate\Database\Eloquent\Model;
	class hello extends Model {
		public function index() {
			return array('msg' => 'hello World!');
		}
	}
?>