<?php
/**
 * Copyright 2010 jubianchi.fr
 * 
 * @author jubianchi
 */

class Caller {
	//Region "Private Props"
	private $_class_    = null;
	private $_name_     = null;
	private $_args_     = array();
	private $_instance_ = null;
	private $_method_   = false;
	private $_function_ = false;
	private $_static_   = false;
	private $_file_     = null;
	private $_line_     = null;
	//Endregion
	
	//Region "Static Methods"
	public static function get($offset = 0) {		
		$trace = self::trace($offset);
		return !isset($trace[0]) ? null : $trace[0];
	}
	
	public static function instance($offset = 0) {
		if(!self::isClassMethod()) return null;
		
		$caller = self::get($offset);
		return isset($caller['object']) ? $caller['object'] : null;
	}
	
	public function trace($offset = 0) {
		$trace = debug_backtrace(true);

		$level = 0;
		while(@$trace[$level]['class'] == 'Caller') {
			unset($trace[$level]);
			$level++;
		}
		unset($trace[$level]);
		
		if($offset > 0) $level += $offset;
		
		return !empty($trace) ? self::castTrace(array_values($trace)) : null;
	}
	
	public static function isClassMethod() {
		$caller = self::get(1);
		
		return ($caller != null && $caller -> _method_);	
	}
	
	public static function isFunction() {
		$caller = self::get(1);

		return ($caller != null && $caller -> _function_);	
	}
	
	public static function isInstanceOf($class) {
		if(!self::isClassMethod()) return false;
		
		if(is_object($class)) $class = get_class($class);
		$caller = self::get(1);
		
		return ($caller -> _instance_ instanceof  $class);
	}
	//Endregion
	
	//Region "Private Methods"
	private static function cast(array $caller) {
		$obj = new Caller();
		
		$obj -> _file_ = $caller['file'];
		$obj -> _line_ = $caller['line'];
		$obj -> _name_ = $caller['function'];
		$obj -> _args_ = $caller['args'];
		
		if(isset($caller['class'])) {
			$obj -> _method_ = true;
			$obj -> _class_  = $caller['class'];
			
			if(isset($caller['type'])) {
				switch($caller['type']) {
					case '->': 
						$obj -> _static_ = false; 
						
						$obj -> _instance_ = $caller['object'];
					break;
					case '::': $obj -> _static_ = true;  break;
				}
			}
		} else {
			$obj -> _function_ = true;
		}
		
		return $obj;
	}
	
	private static function castTrace(array $trace) {
		$arr = array();
		
		foreach($trace as $caller) {
			$arr[] = self::cast($caller);
		}
		
		return $arr;
	}
	
	private function __construct() {
	}
	
	public function __get($name) {
		if(isset($this -> {'_' . $name . '_'})) return $this -> {'_' . $name . '_'};
		return null;
	}
	//Endregion
}
?>