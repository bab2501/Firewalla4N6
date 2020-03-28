<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("function.php"); //load 

$setting = array();
$setting["method"] = "view";
$setting["table"] = false;

$setting["dumplocation"] = '/tmp/';
$setting["dumpfile"] = 'dump.json';

if ($_SERVER['REQUEST_URI'] && $_SERVER['REQUEST_URI'] != "/" )  {
	$ruri = explode("/", $_SERVER['REQUEST_URI']);
	//var_dump($ruri);
	//if (isset($ruri[1]))  )  {
		$setting["method"] = $ruri[1];
	//}
	//if (isset($ruri[2]) )  {
		$setting["table"] = $ruri[2];
	//}
}

if (isset($_GET["dumpfile"]) && !empty($_GET["dumpfile"]) )  {$setting["dumpfile"] = $_GET["dumpfile"];} //insecure
if (isset($_GET["method"]) && !empty($_GET["method"]) )  {$setting["method"] = $_GET["method"];}
if (isset($_GET["table"]) && !empty($_GET["table"]) )  {$setting["table"] = $_GET["table"];}

if(strpos($setting["dumpfile"], "/") !== false){
    trigger_error("Error1");exit();
}
if(!file_exists($setting["dumplocation"].$setting["dumpfile"]) !== false){
    trigger_error("Error2");
	$sorted_array = array(array("empty")); //crachfix #lasyfix
} 
else {
	$sorted_array = json2array($setting["dumplocation"].$setting["dumpfile"],true);
}

$dumpList = array(); //do not use space in name #lasyfix
$dumpList["dump.json"] = "standardDump";
$dumpList["20200223.json"] = "23feb2020";
$dumpList["20200312.json"] = "12mrt2020";
$dumpList["20200328.json"] = "28mrt2020";

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
			$("#tableSelectRadio-'.$setting["table"].'").prop("checked", true);
			$( ".tableSelect input" ).checkboxradio();
			$(".tableSelect input[type=radio]").change(function(){ $(location).attr(\'href\',$(this).val() ); });
			$("#mathodSelect-'.$setting["method"].'").prop("checked", true);
			$( ".mathodSelect input" ).checkboxradio();
			$(".mathodSelect input[type=radio]").change(function(){ $(location).attr(\'href\',$(this).val() ); });
			$("#dumpSelect-'.$dumpList[$setting["dumpfile"]].'").prop("checked", true);
			$( ".dumpSelect input" ).checkboxradio();
			$(".dumpSelect input[type=radio]").change(function(){ $(location).attr(\'href\',$(this).val() ); });
		} );
	</script>
	
</head>
<body>
<h1>Firewalla4N6 - Database View</h1>
method = '.$setting["method"] . '
table = '.$setting["table"] . '
file = '.$setting["dumpfile"] . '
<div class="widget">
';

echo '
<div id="dumpSelect">
	<fieldset class="dumpSelect">
		<legend>Select a Dump: </legend>
		<!-- <label for="dumpSelect-index">index</label> -->
		<!-- <input type="radio" name="dumpSelect" id="dumpSelect-index" value="index"> -->
';
	foreach ($dumpList as $dumpUrl => $dumpName) {
		echo '
			<label for="dumpSelect-'.$dumpName.'">'.$dumpName.'</label>
			<input type="radio" name="dumpSelect" id="dumpSelect-'.$dumpName.'" value="view/index'.'/?dumpfile='.$dumpUrl.'">
		';
	}
echo '</fieldset>';
echo '</div>';

$methodList = array();
$methodList[] = "view";

echo '
<div id="mathodSelect">
	<fieldset class="mathodSelect">
		<legend>Select a Method: </legend>
		<label for="mathodSelect-index">index</label>
		<input type="radio" name="mathodSelect" id="mathodSelect-index" value="index">
';
	foreach ($methodList as $methodId => $methodName) {
		echo '
			<label for="mathodSelect-'.$methodName.'">'.$methodName.'</label>
			<input type="radio" name="mathodSelect" id="mathodSelect-'.$methodName.'" value="'.$methodName.'/index">
		';
	}
echo '</fieldset>';
echo '</div>';


$tableSSelect = showDatabase($sorted_array);
echo '
<div id="tableSelect">
	<fieldset class="tableSelect">
		<legend>Select a Table: </legend>
		<label for="tableSelectRadio-index">All</label>
		<input type="radio" name="tableSelectRadio" id="tableSelectRadio-index" value="view/index'.'/?dumpfile='.$setting["dumpfile"].'">
';
	foreach ($tableSSelect as $tid => $table_name) {
		echo '
			<label for="tableSelectRadio-'.$table_name.'">'.$table_name.'</label>
			<input type="radio" name="tableSelectRadio" id="tableSelectRadio-'.$table_name.'" value="view/'.$table_name.'/?dumpfile='.$setting["dumpfile"].'">
		';
	}
echo '</fieldset>';
echo '</div>';

switch ($setting["method"]) {
	case "view":
		switch ($setting["table"]) {
			case "index":			
				$table_names = showDatabase($sorted_array);
				echo '<div id="tablenames">';
				foreach ($table_names as $tid => $table_name) {
					echo "<h3>".$table_name."</h3>";
					echo '<div id="table_'.$table_name.'">';
					echo showTable($sorted_array,$table_name);
					echo "</div>";
				}
				echo "</div>";
			default:
				//$table_names = showDatabase($sorted_array);
				echo '<div id="tablenames">';
				//foreach ($table_names as $tid => $table_name) {
				$table_name = $setting["table"];
					echo "<h3>".$table_name."</h3>";
					echo '<div id="table_'.$table_name.'">';
					echo showTable($sorted_array,$table_name);
					echo "</div>";
				//}
				echo "</div>";
		}				
	default:
		
}
echo '
</body>
</html>';

exit();
?>
