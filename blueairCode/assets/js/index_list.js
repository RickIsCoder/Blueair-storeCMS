$(document).ready(function(){
	var path = '';
	$(document).delegate('.add','click',function(){
   		var productID = $(this).parents('tr').attr('productID');

   		$.post(
   			path+'admin/store/addStoreProduction',
   			{productID:productID},
   			function(data){
   				if(data == 'error')alert(data);
   			}
   		);

	　　	$(this).attr("disabled",true);
		$(this).parents('tr').children('td').children('.del').attr("disabled",false);
    });

	$(document).delegate('.del','click',function(){
   		var productID = $(this).parents('tr').attr('productID');

   		$.post(
   			path+'admin/store/delStoreProduction',
   			{productID:productID},
   			function(data){
   				if(data == 'error')alert(data);
   			}
   		);

	　　	$(this).attr("disabled",true);
		$(this).parents('tr').children('td').children('.add').attr("disabled",false);
    });
});