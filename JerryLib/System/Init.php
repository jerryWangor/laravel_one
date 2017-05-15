<?php namespace JerryLib\System;

trait Init {
	
    protected static $objects;
	//对象缓存，妈妈再也不用担心对象多次实例化了
    public static function Init($db = '') { //使用DB初始化
        
        if($db) {
            if(!isset(self::$objects[$db])) self::$objects[$db] = new self($db);
            return self::$objects[$db];
        } else {
            return new self();
        }

    }

}