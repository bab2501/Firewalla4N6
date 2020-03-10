<?php

function json2array($url,$dosort=true) {
	$json = file_get_contents('/tmp/dump.json', true);
	$array = json_decode($json, true);
	if ($dosort) {
		$output = $array[0];
		ksort($output);
	}
	else {$output=$array[0];}
	return $output;
}

function showDatabase($sorted_array) {
	foreach ($sorted_array as $key => $value) {
		$meta = explode(":", $key);
		$table[] = $meta[0];
	}
	return array_unique($table);
}

function rowFirstCell($row) {
	if (!isset($row) || empty($row) || !is_array($row)) {return false;}
	$output = array_shift($row); //first cell
	return $output;
}

function rowSecondCell($row) {
	if (!isset($row) || empty($row) || !is_array($row)) {return false;}
	$blackhole[] = array_shift($row); //first cell
	$output = array_shift($row); // second cell
	return $output;
}

function rowLastCell($row) {
	if (!isset($row) || empty($row) || !is_array($row)) {return false;}
	$output = array_pop($row); //last cell
	return $output;
}

function rowBeforeLastCell($row) {
	if (!isset($row) || empty($row) || !is_array($row)) {return false;}
	$blackhole[] = array_pop($row); //last cell
	$output = array_pop($row); // one before last cell
	return $output;
}

function getTable($sorted_array,$table_name) {
	foreach ($sorted_array as $key => $value) {
		$meta = explode(":", $key);
		if ($meta[0] == $table_name) {
			$output[] = $value;
		}
	}
	return $output;
}

function htmlOneTable($table) {
	$output = "<table>";
	foreach ($table as $rid => $row) {
		if (!isset($row) || empty($row) || !is_string($row)) {continue;}
		if (is_array($row)) { echo "error line 36"; }
		//$temprow = $row;
		//$cellA = array_pop($temprow);
		//$cellB = array_pop($temprow);
		//if ($cellA == "0" && is_null($cellB)) {continue;}
		$output .= "<tr>";
			$output .= "<th>".$rid."</th>";
			$output .= "<td>".$row."</td>";
		}
		$output .= "</tr>";
	
	$output .= "</table>";
	return $output;
}
function htmlTwoTable($table) {
	$output = "<table>";
	foreach ($table as $rid => $row) {
		$output .= "<tr>";
		foreach ($row as $rname => $rvalue) {
			$output .= "<th>".$rname."</th>";
		}
		$output .= "</tr>";
		break;
	}
	foreach ($table as $rid => $row) {
		if (!isset($row) || empty($row) || !is_array($row)) {continue;}
		$temprow = $row;
		$cellA = array_pop($temprow);
		$cellB = array_pop($temprow);
		if (rowLastCell($row) == "0" && is_null(rowBeforeLastCell($row))) {continue;}
		$output .= "<tr>";
		foreach ($row as $rname => $rvalue) {
			$output .= "<td>".$rvalue."</td>";
		}
		$output .= "</tr>";
	}
	$output .= "</table>";
	return $output;
}

function htmlAlarmTable($table,$typeAlarm=false) {
	$output = "<table>";
	foreach ($table as $rid => $row) {
		$output .= "<tr>";
		foreach ($row as $rname => $rvalue) {
			$temprowB = $row;
			$cellC = array_shift($temprowB); //first cell
			$cellD = array_shift($temprowB); // second cell
			if ($typeAlarm !== false && $cellD != $typeAlarm) {continue;}
			$output .= "<th>".$rname."</th>";
		}
		$output .= "</tr>";
		break;
	}
	foreach ($table as $rid => $row) {
		if (!isset($row) || empty($row) || !is_array($row)) {continue;}
		$temprowA = $row;
		$temprowB = $row;
		$cellA = array_pop($temprowA); //last cell
		$cellB = array_pop($temprowA); // one before last cell
		$cellC = array_shift($temprowB); //first cell
		$cellD = array_shift($temprowB); // second cell
		if (rowLastCell($row) == "0" && is_null(rowBeforeLastCell($row))) {continue;}
		if ($typeAlarm !== false && rowSecondCell($row) != $typeAlarm) {continue;}
		$output .= "<tr>";
		foreach ($row as $rname => $rvalue) {
			$output .= "<td>".$rvalue."</td>";
		}
		$output .= "</tr>";
	}
	$output .= "</table>";
	return $output;
}


function listAlarmType($table) {
	foreach ($table as $rid => $row) {
		if (!isset($row) || empty($row) || !is_array($row)) {continue;}
		$cellC = array_shift($row); //first cell
		$cellD = array_shift($row); // second cell
		$output[] = $cellD;
	}
	return array_unique($output);
}



function showTable($sorted_array,$table_name) {
	$table = getTable($sorted_array,$table_name);
	$output = "";
	switch ($table_name) {
		case "_alarm":
			$alarmTypes = listAlarmType($table);
			foreach ($alarmTypes as $raid => $alarmTypeName) {
				$output .= "<h3>".$alarmTypeName."</h3>";
				$output .= "<div>";
					$output .= htmlAlarmTable($table,$alarmTypeName);
				$output .= "</div>";
			}
			//$output .= htmlAlarmTable($table,true);

		break;
		case "_alarmDetail":
			$output = htmlTwoTable($table);
		break;
		case "aggrflow":
			$output = htmlTwoTable($table);
		break;
		case "alarm":
			$output = htmlOneTable($table);
		break;
		case "alarm_active":
			$output = htmlTwoTable($table);
		break;
		case "alarm_archive":
			$output = htmlTwoTable($table);
		break;
		case "app":
			$output = htmlOneTable($table);
		break;
		case "boneAPIUsage":
			$output = htmlTwoTable($table);
		break;
		case "bootingComplete":
			$output = htmlOneTable($table);
		break;
		case "bq":
			$output = htmlOneTable($table);
		break;
		case "cache.intel":
			$output = htmlOneTable($table);
		break;
		case "category":
			$output = htmlOneTable($table);
		break;
		case "categoryflow":
			$output = htmlTwoTable($table);
		break;
		case "clients":
			$output = htmlTwoTable($table);
		break;
		case "country":
			$output = htmlTwoTable($table);
		break;
		case "dhcp":
			$output = htmlTwoTable($table);
		break;
		case "dns":
			$output = htmlTwoTable($table);
		break;
		case "dynamicCategoryDomain":
			$output = htmlTwoTable($table);
		break;
		case "ext.safeSearch.config":
			$output = htmlOneTable($table);
		break;
		case "extension.portforward.config":
			$output = htmlOneTable($table);
		break;
		case "firstBinding":
			$output = htmlOneTable($table);
		break;
		case "flow":
			$output = htmlTwoTable($table);
		break;
		case "flowgraph":
			$output = htmlTwoTable($table);
		break;
		case "groupName":
			$output = htmlOneTable($table);
		break;
		case "guessed_router":
			$output = htmlOneTable($table);
		break;
		case "host":
			$output = htmlTwoTable($table);
		break;
		case "intel":
			$output = htmlTwoTable($table);
		break;
		case "lastapp":
			$output = htmlOneTable($table);
		break;
		case "lastcategory":
			$output = htmlOneTable($table);
		break;
		case "lastsumflow":
			$output = htmlOneTable($table);
		break;
		case "migration":
			$output = htmlOneTable($table);
		break;
		case "mode":
			$output = htmlOneTable($table);
		break;
		case "monitored_hosts":
			$output = htmlTwoTable($table);
		break;
		case "monitored_hosts6":
			$output = htmlTwoTable($table);
		break;
		case "neighbor":
			$output = htmlTwoTable($table);
		break;
		case "notice":
			$output = htmlTwoTable($table);
		break;
		case "oldDataMigration":
			$output = htmlOneTable($table);
		break;
		case "policy":
			$output = htmlTwoTable($table);
		break;
		case "policy_active":
			$output = htmlTwoTable($table);
		break;
		case "prod.branch":
			$output = htmlOneTable($table);
		break;
		case "ratelimit":
			$output = htmlTwoTable($table);
		break;
		case "rdns":
			$output = htmlTwoTable($table);
		break;
		case "recommend_firewalla_mode":
			$output = htmlOneTable($table);
		break;
		case "software":
			$output = htmlTwoTable($table);
		break;
		case "srdns":
			$output = htmlTwoTable($table);
		break;
		case "stats":
			$output = htmlTwoTable($table);
		break;
		case "sumflow":
			$output = htmlTwoTable($table);
		break;
		case "sys":
			$output = htmlOneTable($table);
		break;
		case "syssumflow":
			$output = htmlTwoTable($table);
		break;
		case "timedTraffic":
			$output = htmlTwoTable($table);
		break;
		case "unmonitored_hosts_all":
			$output = htmlTwoTable($table);
		break;
		case "user_agent":
			$output = htmlOneTable($table);
		break;
		default:
			$output = "";
	}
	return $output;
}