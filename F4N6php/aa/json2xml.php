<?php

  // Require Array2XML class which takes a PHP array and changes it to XML
  require_once('array2xml.php');

  // Gets JSON file
  $json = file_get_contents('dump.json');

  // Decodes JSON into a PHP array
  $php_array = json_decode($json, true);

  // adding Content Type
  header("Content-type: text/xml");

  // Converts PHP Array to XML with the root element being 'root-element-here'
  $xml = Array2XML::createXML('root-element-here', $php_array);
  
  echo $xml->saveXML();