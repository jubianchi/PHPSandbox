<?php
ini_set('display_errors', 1);

class Caller {
	public static function get($offset = 0) {
		/*$trace = debug_backtrace(false);
		//$trace = self::trace($offset - 1);
		
		$level = 0;
		while(@$trace[$level]['class'] == 'Caller') {
			$level++;
		}
		if($offset > 0) $level += $offset;
		
		if(isset($trace[$level])) {
			$caller = $trace[$level];
			
			return $caller;
		}
		
		return null;*/
		
		$trace = self::trace();
		return !isset($trace[$offset]) ? null : $trace[$offset];
	}
	
	public function trace($offset = 0) {
		$trace = debug_backtrace(false);

		$level = 0;
		while(@$trace[$level]['class'] == 'Caller') {
			unset($trace[$level]);
			$level++;
		}
		unset($trace[$level]);
		
		if($offset > 0) $level += $offset;
		
		return !empty($trace) ? array_values($trace) : null;
	}
	
	public static function isClassMethod() {
		$caller = self::get(1);
		
		return isset($caller['class']);	
	}
	
	public static function isFunction() {
		$caller = self::get(1);
		
		return !isset($caller['class']);	
	}
	
	public static function isInstanceOf($class) {
		if(!self::isClassMethod()) return false;
		
		if(is_object($class)) $class = get_class($class);
		$caller = self::get(1);
		
		return ($caller['class'] == $class);
	}
}

function foo() {
	var_dump(Caller::get());
	var_dump(Caller::trace());
}
function bar() {
	foo();	
}


class Buz {
	function foo() {
		foo();
	}
	function bar() {
		$b = new Baz();
		$b -> bar();
	}
}
class Baz {
	function foo() {
		foo();
	}
	function bar() {
		bar();
	}
}

echo '<pre>';
var_dump(Caller::get());

$baz = new Buz();
$baz -> bar();
echo '</pre>';
?>