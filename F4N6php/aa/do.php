<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Gets JSON file
$json = file_get_contents('/tmp/dump.json', true);

// Decodes JSON into a PHP array
$array = json_decode($json, true);

$saryy = $array[0];
$saryyt = ksort($saryy);

$no = 0;
echo "<table>";
echo "<tr><th>no</th><th>key</th><th>Value</th></tr>";
foreach ($saryy as $key => $value) {
	echo "<tr>";
    echo "<td>{$no}</td>";
    echo "<td>{$key}</td>";
	if (is_array($value)) {
		echo "<td><table>";
		foreach ($value as $kkk => $vvv) {
			echo "<tr>";
			echo "<td>{$kkk}</td>";
			echo "<td>{$vvv}</td>";
			echo "</tr>";
		}
		echo "</table></td>";
	}
	else {
		echo "<td>".$value."</td>";
	}	
	echo "</tr>";
	$no++;
}
echo "</table>";
?>
<style>
table {
  border: 1ps solid black;
}
</style>
