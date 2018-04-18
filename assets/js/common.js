/**
 * @author Kishor Mali
 */


jQuery(document).ready(function(){
	
	jQuery(document).on("click", ".deleteUser", function(){
		var userId = $(this).data("id"),
			hitURL = baseURL + "deleteUser",
			currentRow = $(this);
		
		var confirmation = confirm("Are you sure to delete this user ?"+userId);
		
		if(confirmation)
		{
			jQuery.ajax({
			type : "POST",
			dataType : "json",
			url : hitURL,
			data : { userId : userId } 
			}).done(function(data){
				console.log(data);
				currentRow.parents('tr').remove();
				if(data.status = true) { alert("User successfully deleted"); }
				else if(data.status = false) { alert("User deletion failed"); }
				else { alert("Access denied..!"); }
			});
		}
	});
});
	jQuery(document).ready(function(){
	
		jQuery(document).on("click", ".deleteServer", function(){
			var id = $(this).data("id"),
				hitURL = baseURL + "deleteServer",
				currentRow = $(this);
				
			var confirmation = confirm("Are you sure to delete this server ?");
			
			if(confirmation)
			{
				jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { id : id } 
				}).done(function(data){
					console.log(data);
					currentRow.parents('tr').remove();
					if(data.status = true) { alert("Server successfully deleted"); }
					else if(data.status = false) { alert("Server deletion failed"); }
					else { alert("Access denied..!"); }
				});
			}
		});
	});
	jQuery(document).ready(function(){
	
		jQuery(document).on("click", ".deleteBackup", function(){
			var id = $(this).data("id"),
				hitURL = baseURL + "deleteBackup",
				currentRow = $(this);
			
			var confirmation = confirm("Are you sure to delete this backup ?");
			
			if(confirmation)
			{
				jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { id : id } 
				}).done(function(data){
					console.log(data);
					currentRow.parents('tr').remove();
					if(data.status = true) { alert("Backup successfully deleted"); }
					else if(data.status = false) { alert("Backup deletion failed"); }
					else { alert("Access denied..!"); }
				});
			}  
		});
	});

	jQuery(document).ready(function(){
	
		jQuery(document).on("click", ".deleteClient", function(){
			var id = $(this).data("id"),
				hitURL = baseURL + "deleteClient",
				currentRow = $(this);
			
			var confirmation = confirm("Are you sure to delete this client ?");
			
			if(confirmation)
			{
				jQuery.ajax({
				type : "POST",
				dataType : "json",
				url : hitURL,
				data : { id : id } 
				}).done(function(data){
					console.log(data);
					currentRow.parents('tr').remove();
					if(data.status = true) { alert("Client successfully deleted"); }
					else if(data.status = false) { alert("Client deletion failed"); }
					else { alert("Access denied..!"); }
				});
			}
		});
	});
	

jQuery(document).on("click", ".searchList", function(){
	
});
