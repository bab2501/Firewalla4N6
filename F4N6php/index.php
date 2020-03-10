<?php
//error_reporting(-1);

echo "x";

exit();
$file = file_get_contents('/tmp/dump.json', true);
$obj = (json_decode($file);

$data = array('total_stud' => 500);

// creating object of SimpleXMLElement
$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');

var_dump($result = $xml_data->asXML(););
?>
