$(document).ready(function(){


console.log("Ready!");

jQuery(".addUser").submit(function(event){

	var tdata = jQuery(this).serialize();
	console.log(tdata);
	
	jQuery.ajax({
		url: 'ajax/addUser.php',
		type: 'post',
		data: tdata,
		dataType: 'json',
		beforeSend: function(){
			console.log("Load");
			jQuery(".loading.mini").css("display", "flex");
			jQuery(".loading.mini .loader").fadeIn("slow");
		},
		success: function(json){
			if (json.result){
				alert("Пользователь добавлен!");
			}else{
				alert("Error!");
			}
			jQuery(".addUser").trigger('reset');
		},
		complete: function(){
			jQuery(".loading.mini").delay(0).fadeOut();
			jQuery(".loading.mini .loader").delay(0).fadeOut("slow");
		}
	});
	
	event.preventDefault();

})


})