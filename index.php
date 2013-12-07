<link rel="stylesheet" type="text/css" href="style.css">
<script src="jquery.js"></script>
<?php
require_once 'config.php';

$source_initial_sql = "SELECT TABLE_CATALOG,
        TABLE_SCHEMA,
        TABLE_NAME,
        TABLE_TYPE
		FROM INFORMATION_SCHEMA.TABLES
		WHERE TABLE_TYPE = 'BASE TABLE'";
$source_result = odbc_exec($source_connection, $source_initial_sql);
?>

<div id="source_container">
	<select id="source_tables">
		<option value="">--select a table--</option>
	<?php
	while(odbc_fetch_row($source_result)){
		$table_name = odbc_result($source_result, "TABLE_NAME");
	?>
		<option value="<?php echo $table_name; ?>"><?php echo $table_name; ?></option>	
	<?php	
	}		
	?>
	</select>
	<div id="source_field_container" class="field_container">
	</div>
</div>

<?php
$destination_initial_sql = "SELECT DISTINCT TABLE_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = '$to_database';";

$destination_result = $destination_connection->query($destination_initial_sql);    
?>

<div id="destination_container">
	<select id="destination_tables">
		<option value="">--select a table--</option>
		<?php
		while($row = $destination_result->fetch_object()){
		?>
		<option value="<?php echo $row->TABLE_NAME; ?>"><?php echo $row->TABLE_NAME; ?></option>
		<?php	
		}
		?>
	</select>

	<textarea rows="5" cols="70" id="query"></textarea>
	<input type="button" id="import" value="import" />
	<div id="message"></div>
</div>

<script src="main.js"></script>