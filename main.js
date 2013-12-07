var destination_fields;

$('#source_tables').change(function(){
	var self = $(this);
	var table = self.val();

	$('#query').val("SELECT * FROM " + table);

	$.post(
		'get_fields.php', 
		{
			'type' : 'source',
			'table' : table
		},
		function(response){
			var fields = JSON.parse(response);
			var field_count = fields.length;
			
			var options = "";
			for(var x = 0; x < field_count; x++){
				options += "<li><input type='checkbox' class='field' value='" + fields[x] + "'/>" + fields[x] + "<span></span></li>";
			}
			
			$('.field_container').html(options);
		}
	);
});

$('#destination_tables').change(function(){
	var self = $(this);
	var table = self.val();
	$.post(
		'get_fields.php', 
		{
			'type' : 'destination',
			'table' : table
		},
		function(response){
			destination_fields = JSON.parse(response);
			
		}
	);

});

$('#source_field_container').on('click', '.field', function(){

	var self = $(this);
	
	if(self.is(':checked')){

		var field_count = destination_fields.length;

		if(field_count > 0){
			var select = $('<select>').addClass('dest_field');
			var options = '';
			for(var x = 0; x < field_count; x++){
				options += "<option value='" + destination_fields[x] + "'>" + destination_fields[x] + "</option>";
			}
			
			select.append(options);
			self.siblings('span').html(select);
		}
	}else{
		self.siblings('span').html('');
	}

	var query = "SELECT ";
	var table = $('#source_tables').val();
	var source_field_count = $('.field:checked').length - 1;
	

	$('.field:checked').each(function(index){
		
		var field = $(this).val();
		query += field; 

		if(index < source_field_count) query += ", ";
	});
	query += " FROM " + table;
	$('#query').val(query);	
});

$('#import').click(function(){

	var source_table = $('#source_tables').val();
	var dest_table = $('#destination_tables').val();

	var query = $('#query').val();

	var source_fields = [];
	var dest_fields = [];

	$('.field:checked').each(function(){
		var self = $(this);
		var source_field = self.val();
		var dest_field = self.siblings('span').children('select').val();

		source_fields.push(source_field);
		dest_fields.push(dest_field);
	});

	var field_count = source_fields.length;

	$.post(
		'importer.php',
		{
			'query' : query,
			'source_table' : source_table,
			'dest_table' : dest_table,
			'source_fields' : source_fields,
			'dest_fields' : dest_fields,
			'field_count' : field_count
		},
		function(response){
			$('#message').text(response);
		}
	);
});