$(function ()
{
    
    $('.delCompare').click(function(){
        var productionID = $(this).closest('.compare-item').attr('productionID');
        $(this).closest('.compare-item').remove();
    	$.post(
    		'delCompare',
    		{productionID:productionID},
    		function(data){
    			if(data !='success') alert('ERROR!');
    		}
    	);
    });

    $('.addCompare').click(function(){
        var productionID = $(this).closest('.production').attr('productionID');
        $.post(
            'addCompare',
            {productionID:productionID,page_position:'compare'},
            function(data){
                switch  (data){
                    case '已经加入对比':;
                    case '最多可以对比3个产品': alert(data);
                        break;
                    default :
                        var data=JSON.parse(data);
                        var html = '';
                        html = '<div>'+data[0].productName+'</div><hr />'//只显示一条，做示例
                        $('.compare').append(html);
                        break;

                }
            }
        );
    });
});