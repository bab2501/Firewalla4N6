<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


echo '<!DOCTYPE html>

<html lang="nl-NL">
  <head>
		<base href="http://9.blaauwgeers.amsterdam"><!--[if lte IE 6]></base><![endif]-->
		<title>9 Blaauwgeers</title>
		<meta name="generator" content="" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="/favicon.ico" />
		<!-- <link rel="stylesheet" type="text/css" href="http://alexander.blaauwgeers.com/layout.css" /> -->
	<style>
		table td {
			border: 1px solid black;
			padding: 2px;
			height: 15px;
		}
		table * {
			padding: 2px;
			height: 15px;
			margin: 0px;
		}
	</style>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="/resources/demos/style.css">
	<script src="/static/js/jquery-1.12.4.js"></script>
	<script src="/static/js/jquery-ui.js"></script>
	<script>
		$( function() {
			$( "#tablenames" ).accordion({
				collapsible: true,
				heightStyle: "content",
				active: false
			});
			$( "#table__alarm" ).accordion({
				collapsible: true,
				heightStyle: "content",
				active: false
			});
		} );
	</script>
	
</head>
<body>
<h1>Firewalla4N6 - Database View</h1>
';

require_once("function.php"); //load 
$sorted_array = json2array('/tmp/dump.json',true);

$table_names = showDatabase($sorted_array);
echo '<div id="tablenames">';
foreach ($table_names as $tid => $table_name) {
	echo "<h3>".$table_name."</h3>";
	echo '<div id="table_'.$table_name.'">';
	echo showTable($sorted_array,$table_name);
	echo "</div>";
}
echo "</div>";

echo '
</body>
</html>';

exit();
?>
