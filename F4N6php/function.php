<?php

function json2array($url,$dosort=true) {
	if(!isset($url)) {trigger_error("$url emt");}
	$json = file_get_contents($url, true);
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

function rowMatch($row,$key,$value=false) {
	if (!isset($row) || empty($row) || !is_array($row)) {return false;}
	if (!isset($row[$key])) {return false;} // key is not present in row.
	if ($value==false) {return $row[$key];} // value is not given
	if ($row[$key]==$value) {return true;} // value match
	return false;
}

function humanReadableValue($input) {
	//if(!is_numeric(floatval($input))) {return $input;}
    //if(strtotime(date('d-m-Y H:i:s',floatval($input))) === (float)floatval($input)) { return date('d-m-Y H:i:s',floatval($input)); }
    //else return $input;
    return $input;
}

function getTable($sorted_array,$table_name) {
	$output = array();
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
		$output .= "<tr>";
			$output .= "<th>".$rid."</th>";
			$output .= "<td>".$row."</td>";
		}
		$output .= "</tr>";
	
	$output .= "</table>";
	return $output;
}
function htmlTwoTable($table,$horizontal=false) {
	$output = "<table>";
	$keycol = array();
	$keyrow = array();
	$keyreset = array();
	
	foreach ($table as $rid => $row) {
		//if (!rowMatch($row,"type",$typeAlarm)) {continue;}
		$keycol = array_unique(array_merge($keycol,array_keys($row)));
		//break;
	}
	$output .= "<tr>";
	foreach ($keycol as $rid => $rkey) {
		$keyrow[$rkey] = "NULL";
		if($horizontal) { continue; }
		else {$output .= "<th>".$rkey."</th>";} //table header
	}
	if($horizontal) { $output .= "<th>A</th><th>B</th>"; }
	$output .= "</tr>";
	$keyreset = array_merge($keyrow); //backup empty array
	
	foreach ($table as $rid => $row) {
		if (!isset($row) || empty($row) || !is_array($row)) {continue;}
		if (rowLastCell($row) == "0" && is_null(rowBeforeLastCell($row))) {continue;}
		//if (!rowMatch($row,"type",$typeAlarm)) {continue;}
		foreach ($row as $rname => $rvalue) {
			$keyrow[$rname] = $rvalue;
		}
		if(!$horizontal) { $output .= "<tr>"; }
		foreach ($keyrow as $rname => $rvalue) {
			if($horizontal) { $output .= "<tr>"; }
			if($horizontal) { $output .= "<th>".$rname."</th>";; }
			$output .= "<td title=\"".$rname."\">".$rvalue."</td>";
			if($horizontal) { $output .= "</tr>"; }
		}
		if(!$horizontal) { $output .= "</tr>"; }
		unset($keyrow);$keyrow = array();$keyrow = array_merge($keyreset);
	}
	$output .= "</table>";
	return $output;
}

function htmlAlarmTable($table,$typeAlarm=false) {
	$output = "<table>";
	$keycol = array();
	$keyrow = array();
	$keyreset = array();
	
	foreach ($table as $rid => $row) {
		if (!rowMatch($row,"type",$typeAlarm)) {continue;}
		$keycol = array_unique(array_merge($keycol,array_keys($row)));
		//break;
	}
	$output .= "<tr>";
	foreach ($keycol as $rid => $rkey) {
		$keyrow[$rkey] = "NULL";
		$output .= "<th>".$rkey."</th>"; //table header
	}
	$output .= "</tr>";
	$keyreset = array_merge($keyrow); //backup empty array
	
	foreach ($table as $rid => $row) {
		if (!isset($row) || empty($row) || !is_array($row)) {continue;}
		if (rowLastCell($row) == "0" && is_null(rowBeforeLastCell($row))) {continue;}
		if (!rowMatch($row,"type",$typeAlarm)) {continue;}
		foreach ($row as $rname => $rvalue) {
			$keyrow[$rname] = $rvalue;
		}
		$output .= "<tr>";
		foreach ($keyrow as $rname => $rvalue) {
			$output .= "<td title=\"".$rname."\">".humanReadableValue($rvalue)."</td>";
		}
		$output .= "</tr>";
		unset($keyrow);$keyrow = array();$keyrow = array_merge($keyreset);
	}
	$output .= "</table>";
	return $output;
}


function listAlarmType($table) {
	foreach ($table as $rid => $row) {
		$output[] = rowMatch($row,"type",false);
	}
	return array_unique($output);
}



function showTable($sorted_array,$table_name) {
	$table = getTable($sorted_array,$table_name);
	$output = "";
	switch ($table_name) {
		case "_alarm":
			$alarmTypes = listAlarmType($table);
			//var_dump($alarmTypes);
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
			$output = htmlTwoTable($table,true);
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
			$output = htmlTwoTable($table,true);
		break;
		case "clients":
			$output = htmlTwoTable($table,true);
		break;
		case "country":
			$output = htmlTwoTable($table,true);
		break;
		case "dhcp":
			$output = htmlTwoTable($table);
		break;
		case "dns":
			$output = htmlTwoTable($table,true);
		break;
		case "dnsdmasq":
			$output = htmlOneTable($table);
		break;
		case "dynamicCategoryDomain":
			$output = htmlTwoTable($table,true);
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
			$output = htmlTwoTable($table,true);
		break;
		case "monitored_hosts6":
			$output = htmlTwoTable($table,true);
		break;
		case "neighbor":
			$output = htmlTwoTable($table,false);
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
			$output = htmlTwoTable($table,true);
		break;
		case "prod.branch":
			$output = htmlOneTable($table);
		break;
		case "ratelimit":
			$output = htmlTwoTable($table,true);
		break;
		case "rdns":
			$output = htmlTwoTable($table);
		break;
		case "recommend_firewalla_mode":
			$output = htmlOneTable($table);
		break;
		case "software":
			$output = htmlTwoTable($table, true);
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
			$output = "default view";
			$output .= htmlTwoTable($table);
	}
	return $output;
}