jQuery.noConflict();

jQuery(document).ready(function($) {

	$(".orderBy").click(function(){

		var selected = $("#roles").val();
		console.log(selected);
		console.log($(".orderBy").attr('id'));
		var data = {
	          'action': 'render_rgbcode_users_table_rows',
	          'order' : this.id,
	          'role' : selected
	        };
	        
		$.post(ajaxurl,data, function(response){
		    
		    $("#user-table-body").empty();
		    $("#user-table-body").append(response);
		    if($(".orderBy").attr('id') == 'asc'){
		    	$(".orderBy").attr('id', 'desc');
		    }else{
		    	$(".orderBy").attr('id', 'asc');
		    }
		});
		return true;
	});

	$("#roles").change(function(){

		var selected = $(this).val();
		var orderBy = $(".orderBy").attr('id');
		console.log(orderBy);
		if(orderBy == 'asc'){
			orderBy = 'desc';
	    }else{
	    	orderBy = 'asc';
	    }
		console.log(selected);
		console.log(orderBy);
		var data = {
	          'action': 'render_rgbcode_users_table_rows',
	          'order' : orderBy,
	          'role' : selected
	        };
	        
		$.post(ajaxurl,data, function(response){
		    
		    $("#user-table-body").empty();
		    $("#user-table-body").append(response);
		});
		return true;
	});
});