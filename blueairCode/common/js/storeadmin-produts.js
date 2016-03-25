$(function(){
	//点击加载系列的产品
	$(".series-tab").click(function(){
        $(".series-tab").removeClass("active");
        $(this).addClass("active");
		var serieID = $(this).data("serieid");
        updateProduction(serieID);
	});
    
    function updateProduction(serieID){
        $.post('getProductList',{serieID:serieID},function(data){
            var pHtml = "";
            for(var i = 0; i < data.length; i++){
                var product = data[i];
                product.isOnline = Boolean(product.isOnline);
                pHtml += "<div class='product "+(product.isOnline ? "online" : "offline")+"' data-productid='"+product.productID+"'>";
                pHtml += "<div class='status'>"+(product.isOnline ? "上架中" : "已下架")+"</div>";
                pHtml += "<div class='product-image-container' style='background-image:url("+product.picture_path+")'></div>"
                pHtml += "<div class='product-name'>"+product.productName+"</div>"
                pHtml += "<div class='button-container'><button class='product-btn btn btn-default ' data-productid='"+product.productID+"' data-online='"+product.isOnline+"'>"+(product.isOnline ? "下 架" : "上 架")+"</button></div>"
                pHtml += "</div>";
            }
            $("#production").html(pHtml);
		}, 'JSON');
    }
    
    updateProduction(25);
    
    $("#production").delegate(".product-bt", "click", function(){
        var bt = $(this);
        var pId = bt.data("productid");
        var url = bt.data("online") ? "delStoreProduction" : "addStoreProduction";
        $.ajax({
            url:url,
            method:"POST",
            data:{productID:pId},
            success:function(data){
                if(data == "success"){
                    alert("操作成功。");
                    var serieID = $(".series-tab.active").data("serieid");
                    updateProduction(serieID);
                }
                else if(data == "failure"){
                    alert("操作失败");
                }
            }
        });
    });
});
