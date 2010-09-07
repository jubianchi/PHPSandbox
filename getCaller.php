<?php
/**
 * Returns the caller function/method
 * For more info about the format, please refer to http://php.net/debug_backtrace
 * 
 * @param int $level
 * @return array|null
 */
function getCaller($level = 1) {
	$trace = debug_backtrace(false);
	
	return isset($trace[$level]) ? $trace[$level] : null;
}
?>