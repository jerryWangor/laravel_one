<?php

	/**
	 * 调试不断点
	 * @author  Typ.    date:2016-06-04

	 */
	function xmp($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
	}
	/**
	 * 调试并断点
	 * @author  Typ.    date:2016-06-04

	 */
	function stop($data) {
	    echo '<pre>';
	    var_dump($data);
	    echo '</pre>';
	    exit;
	}
?>