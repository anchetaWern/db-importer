<?php
require_once 'config.php';

$query = $_POST['query'];
$source_fields = $_POST['source_fields'];
$dest_fields = $_POST['dest_fields'];
$field_count = (int) $_POST['field_count'] - 1;
$source_table = $_POST['source_table'];
$dest_table = $_POST['dest_table'];


$source_result = odbc_exec($source_connection, $query);

$insert_query = "INSERT INTO " . $dest_table . " (" . implode(", ", $dest_fields)  . ") VALUES ";
$total_rows = odbc_num_rows($source_result);

$index = 1;
while(odbc_fetch_row($source_result)){

	$field_values = array();
	foreach($source_fields as $field){
		
		$field_value = odbc_result($source_result, $field);
		$field_values[] = $field_value;
	}

	$insert_query .= "('" . implode("','", $field_values) . "')";
	if($index < $total_rows) $insert_query .= ", ";
	$index++;
}

$destination_connection->query($insert_query);
echo $destination_connection->affected_rows . " rows affected";


