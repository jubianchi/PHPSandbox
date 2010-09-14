<?php
/**
 * Copyright 2010 jubianchi.fr
 * 
 * @author jubianchi
 */

class CSS2XPath {
	public static $classes = 0;
	
	private static function axes($selector) {
		$selector = trim($selector);
		
		$selector = str_replace(' + ', '+', $selector);
		$selector = str_replace(' > ', '>', $selector);
		
		$selector = preg_replace('�\+�', '/following-sibling::', $selector);
		$selector = preg_replace('�\>�', '/', $selector);
		
		return $selector;
	}
	
	private static function attributes($selector) {
		$selector = trim($selector);
		
		$selector = preg_replace('�\[([A-Za-z0-9\-_]*)=([A-Za-z0-9\-_]*)\]�', '[@$1="$2"]', $selector);
		$selector = preg_replace('�\[([A-Za-z0-9\-_]*)\~=([A-Za-z0-9\-_]*)\]�', '[contains(concat(" ", @$1, " "),concat(" ", "$2", " "))]', $selector);
		$selector = preg_replace('�\[([A-Za-z0-9\-_]*)\|=([A-Za-z0-9\-_]*)\]�', '[@$1="$2" or starts-with(@$1, concat("$2", "-"))]', $selector);
		$selector = preg_replace('�\[([A-Za-z0-9\-_]*)\*=([A-Za-z0-9\-_]*)\]�', '[contains(@$1, "$2")]', $selector);
		$selector = preg_replace('�\[([A-Za-z0-9\-_]*)\]�', '[@$1]', $selector);
		
		return $selector;
	}
	
	public function ids($selector) {
		$selector = trim($selector);
		
		$selector = preg_replace_callback('�([A-Za-z0-9\-_]?)\#([A-Za-z0-9\-_]*)�', function($m) {
			return ($m[1] == '' ? '*' : $m[1]) . '[@id="' . $m[2] . '"]';
		}, $selector);
		
		return $selector;
	}
	
	public static function classes($selector) {
		$selector = trim($selector);
		
		$selector = preg_replace_callback('�([A-Za-z0-9\-_]*){0,1}(\.([A-Za-z0-9\-_]*))�', function($m) {
			$ret = false;
			
			if(isset($m[3])) {
				if(CSS2XPath::$classes == 0) $ret .= ($m[1] == '' ? '*' : $m[1]);
				
				$ret .= '[contains(concat(" ", @class, " "),concat(" ", "' . $m[3] . '", " "))]';
				CSS2XPath::$classes++;
			}
			
			return $ret;
		}, $selector);
		
		return $selector;
	}
	
	public static function pclasses($selector) {
		$selector = trim($selector);
		
		$selector = preg_replace('�\:nth\-child\(([0-9]*)\)�', '[$1]', $selector);
		$selector = preg_replace('�\:eq\(([0-9]*)\)�', '[$1]', $selector);
		$selector = preg_replace('�\:first\-child�', '[1]', $selector);
		$selector = preg_replace('�\:first�', '[1]', $selector);
		$selector = preg_replace('�\:last\-child�', '[last()]', $selector);
		$selector = preg_replace('�\:last�', '[last()]', $selector);
		$selector = preg_replace_callback('�([A-Za-z0-9\-_]?)\:link�', function($m) {
			return ($m[1] == '' ? '*' : $m[1]) . '[@href]';
		}, $selector);
		//$selector = preg_replace('�:input�', CSS2XPath('input, select, textarea, button'), $selector);
		
		return $selector;
	}
	
	public static function parse($selector) {
		$selector = trim($selector);
		$selectors = explode(',', $selector);
		
		foreach($selectors as $k => $selector) {
			$selector = '//' . trim($selector);
			
			$selector = self::axes($selector);
			$selector = self::attributes($selector);
			$selector = self::pclasses($selector);
			$selector = self::ids($selector);
			static::$classes = 0;
			$selector = self::classes($selector);
			$selector = str_replace(' ', '//', $selector);
			$selectors[$k] = $selector;
		}
		
		$selector = implode(' | ', $selectors);
		return $selector;
	}
}
?>