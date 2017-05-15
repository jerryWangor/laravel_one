<?php
	namespace JerryLib;
	use JerryLib\System\Log;
	
	require_once 'System\Log.php';
	
	var_dump(array_filter(array(1,2,3,4,51,6,1,2,3,'','')));
	var_dump(array(1,2,3,4,51,6,1,2,3,'',''));
	// Log::getInstance()->log('what this!');
	// Log::getInstance()->clear();

?>