<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Gets JSON file
$json = file_get_contents('/tmp/dump.json', true);

// Decodes JSON into a PHP array
$php_array = json_decode($json, true);

// adding Content Type
header("Content-type: text/xml");

function array_to_xml( $data, &$xml_data ) {
    foreach( $data as $key => $value ) {
        if( is_array($value) ) {
            if( is_numeric($key) ){
                $key = 'item'.$key; //dealing with <0/>..<n/> issues
            }
            $subnode = $xml_data->addChild($key);
            array_to_xml($value, $subnode);
        } else {
            $xml_data->addChild("$key",htmlspecialchars("$value"));
        }
     }
}

// initializing or creating array
$data = array('total_stud' => 500);

// creating object of SimpleXMLElement
$xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');

// function call to convert array to xml
array_to_xml($php_array,$xml_data);

//saving generated xml file; 
$result = $xml_data->asXML();
echo $result;


?>
