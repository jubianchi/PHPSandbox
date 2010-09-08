<?php
ini_set('display_errors', 1);

/**
 * Returns the caller function/method
 * For more info about the format, please refer to http://php.net/debug_backtrace
 * 
 * @param string $css
 * @return string
 */
function CSS2XPath($selector) {
	$selector = trim($selector);
	$selector = preg_replace('‘[ ]{0,1}\+[ ]{0,1}‘', '/following-sibling::$1', $selector);
	$selector = preg_replace('‘[ ]{0,1}\>[ ]{0,1}‘', '/', $selector);
	$selector = preg_replace('‘[ ]{0,1}\,[ ]{0,1}‘', '__|__//', $selector);
	$selector = preg_replace('‘[ ]{1}‘', '//', $selector);
	
	$selector = preg_replace('‘\[([A-Za-z0-9\-_]*)=([A-Za-z0-9\-_]*)\]‘', '[@$1="$2"]', $selector);
	$selector = preg_replace('‘\[([A-Za-z0-9\-_]*)~=([A-Za-z0-9\-_]*)\]‘', '[contains(concat(" ", @$1, " "),concat(" ", "$2", " "))]', $selector);
	$selector = preg_replace('‘\[([A-Za-z0-9\-_]*)|=([A-Za-z0-9\-_]*)\]‘', '[@$1="$2" or starts-with(@$1, concat("$2", "-"))]', $selector);
	$selector = preg_replace('‘\[([A-Za-z0-9\-_]*)\*=([A-Za-z0-9\-_]*)\]‘', '[contains(@$1, "$2")]', $selector);
	$selector = preg_replace('‘\[([A-Za-z0-9\-_]*)\]‘', '[@$1]', $selector);
	
	$selector = preg_replace_callback('‘([A-Za-z0-9\-_]?)\.([A-Za-z0-9\-_]*)‘', function($m) {
		return ($m[1] == '' ? '*' : $m[1]) . '[contains(concat(" ", @class, " "),concat(" ", "' . $m[2] . '", " "))]';
	}, $selector);
	
	$selector = preg_replace_callback('‘([A-Za-z0-9\-_]?)\#([A-Za-z0-9\-_]*)‘', function($m) {
		return ($m[1] == '' ? '*' : $m[1]) . '[@id="' . $m[2] . '"]';
	}, $selector);
	
	$selector = preg_replace('‘:nth\-child\(([0-9]*)\)‘', '[$1]', $selector);
	$selector = preg_replace('‘:eq\(([0-9]*)\)‘', '[$1]', $selector);
	$selector = preg_replace('‘:first\-child‘', '[1]', $selector);
	$selector = preg_replace('‘:first‘', '[1]', $selector);
	$selector = preg_replace('‘:last\-child‘', '[last()]', $selector);
	$selector = preg_replace('‘:last‘', '[last()]', $selector);
	$selector = preg_replace('‘:link‘', '[@href]', $selector);
	
	$selector = '//' . str_replace('__', ' ', $selector);
	
	return $selector;
}

echo '<pre>';
echo CSS2XPath('body');
echo "\n";
echo CSS2XPath('.aclass');
echo "\n";
echo CSS2XPath('#anid');
echo "\n";
echo CSS2XPath('p.aclass');
echo "\n";
echo CSS2XPath('p#anid');
echo "\n\n";

echo CSS2XPath('body p');
echo "\n";
echo CSS2XPath('body .aclass');
echo "\n";
echo CSS2XPath('body #anid');
echo "\n";
echo CSS2XPath('body p.aclass');
echo "\n";
echo CSS2XPath('body p#anid');
echo "\n\n";

echo CSS2XPath('body + p');
echo "\n";
echo CSS2XPath('body + .aclass');
echo "\n";
echo CSS2XPath('body + #anid');
echo "\n";
echo CSS2XPath('body + p.aclass');
echo "\n";
echo CSS2XPath('body + p#anid');
echo "\n\n";


echo CSS2XPath('body > p');
echo "\n";
echo CSS2XPath('body > .aclass');
echo "\n";
echo CSS2XPath('body > #anid');
echo "\n";
echo CSS2XPath('body > p.aclass');
echo "\n";
echo CSS2XPath('body > p#anid');
echo "\n\n";

echo CSS2XPath('a[href]');
echo "\n";
echo CSS2XPath('input[type=text]');
echo "\n";

$html = <<<EOT
<html>
<head>
	<title>A Test Title</title>
</head>

<body>
	<p>A test paragraph</p>
	
	<p class="form-entry form-entry-test">
		<label for="txtTest">A test label</label>
		<input type="text" name="txtTest" id="txtTest" />
	</p>
</body>
</html>
EOT;

$dom = new DOMDocument();
$dom -> loadHTML($html);

$x = new DOMXPath($dom);
echo CSS2XPath('body .form-entry');
var_dump($x -> query(CSS2XPath('body .form-entry')) -> length);
?>