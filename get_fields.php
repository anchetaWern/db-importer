<?php
require 'config.php';

$type = $_POST['type'];
$table = $_POST['table'];

$fields = array();

$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME = '$table'";

if($type == 'source'){

	$result = odbc_exec($source_connection, $sql);
	while(odbc_fetch_row($result)){
		$field = odbc_result($result, 'COLUMN_NAME');
		$fields[] = $field;
	}
}else if($type == 'destination'){

	$result = $destination_connection->query($sql);
	while($row = $result->fetch_object()){
		$fields[] = $row->COLUMN_NAME;
	}		
}

echo json_encode($fields);
?>