$(function ()
{  
    $('#addCompare').click(function(){
    	var productionID = $('#title').attr('productionID');
    	$.post
        (
    		'../addCompare',
    		{productionID:productionID},
    		function(data){
    			//if(data='success') alert('加入对比成功');
    			alert(data);
    		}
    	);
    });



    
});