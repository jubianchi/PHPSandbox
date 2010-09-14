<?php
/**
 * Copyright 2010 jubianchi.fr
 * 
 * @author jubianchi
 */

ini_set('display_errors', 1);

$url = null;
$selector = null;
if(isset($_GET['txtUrl'])) $url = $_GET['txtUrl'];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	require_once('../CSS2XPath.php');
	
	$selector = $_POST['txtSelector'];
	$xpath = CSS2XPath::parse($selector);
	
	function dumpNodeList(DOMNodeList $list) {
		//echo '<b>Found ' . $list -> length . ' element(s)</b>';
		echo '<pre style="height: 190px; overflow: auto">';
		
		foreach($list as $node) {
			echo $node -> localName . '(';
			
			$sep = '';
			foreach($node -> attributes as $attr) {
				echo $sep . $attr -> name . '="' . $attr -> value . '"';
				$sep = ', ';
			}
			
			echo ')' . "\n";
		}
		
		echo '</pre>';
	}
}
?>
<html>
<head>
	<title>CSS2XPath Test</title>
	<script type="text/javascript" src="http://static.jubianchi.fr/js/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://static.jubianchi.fr/js/jquery-ui/1.8.1/jquery.ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://static.jubianchi.fr/js/jquery-ui/1.8.1/css/smoothness/jquery-ui-1.8.1.custom.css" />
	<script type="text/javascript">
	$(function() {
		$("#tabs").tabs();
	});
	</script>
	<style type="text/css">
	body {
		font-size: 12px;
	}
	
	.ui-widget, .ui-widget-header, .ui-state-highlight, .ui-state-error {
		padding: 5px;
	}
	ul {
		margin: 2px 5px 2px 0px;
	}
	</style>
</head>

<body>

<div id="tabs">
	<ul>
		<li><a>CSS2XPath</a></li>
		<li><a href="#tabs-1">Browse</a></li>
		<li><a href="#tabs-2">Query</a></li>
	</ul>
	<div id="tabs-1">
		<form action="#" method="get">
			<p>
				<label for="txtUrl" style="width: 5%">URL</label>
				<input type="text" name="txtUrl" id="txtUrl" style="width: 90%" value="<?php echo $url; ?>" placeholder="http://"/>
				<input type="submit" name="sbmUrl" id="sbmUrl"  style="width: 5%" value="Go"/>
			</p>
		</form>
		
		<iframe id="navigator" style="width: 100%; height: 500px; border: 0" src="<?php echo $url; ?>"></iframe>
	</div>
	<div id="tabs-2">
		<form action="#tabs-2" method="post">
			<p>
				<label for="txtSelector">CSS Selector</label><br />
				<textarea name="txtSelector" id="txtSelector" style="width: 100%; height: 1.5em"><?php echo $selector; ?></textarea>
			</p>
			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all">		
				Supported : 
				<ul>
					<li>Simple CSS selectors</li>
					<li>Descendant : +</li>
					<li>Sibling : &gt;</li>
					<li>Attributes selectors</li>
					<li>Attributes value selectors (=, |=, ~=, *=)</li>
					<li>Class selectors</li>
					<li>ID selectors</li>
					<li>Pseudo-classes : :first-child, :first, :last-child, :last, nth-child(), :eq(), :link
				</ul>
				</div>
			</div>
			<p>
				<input type="submit" name="sbmSelector" value="Test selector"/>
			</p>
		</form>
		
		<div class="ui-widget ui-widget-content ui-corner-all">
			<?php
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$dom = new DOMDocument();
				$dom -> loadHTML(file_get_contents($_GET['txtUrl']));
				$x = new DOMXPath($dom);
				
				echo '<div class="ui-widget-header ui-corner-all">CSS : ' . $selector . ' --> XPath : ' . $xpath . '</div>';
				dumpNodeList($x -> query($xpath));
			}
			?>
		</div>
	</div>
</div>

</body>
</html>