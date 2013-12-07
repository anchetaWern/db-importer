<?php
$config = file_get_contents('db-config.json');
$db_config = json_decode($config);

$source = $db_config->source;

$from_server = $source->server;
$from_database = $source->database;
$from_user = $source->user;
$from_password = $source->password;

$destination = $db_config->destination;

$to_server = $destination->server;
$to_database = $destination->database;
$to_user = $destination->user;
$to_password = $destination->password;

$source_connection = odbc_connect("Driver={SQL Server};Server=$from_server;Database=$from_database;", $from_user, $from_password);

$destination_connection = new Mysqli($to_server, $to_user, $to_password, $to_database);
?>