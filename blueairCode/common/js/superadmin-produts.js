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
                pHtml += "<div class='product-image-container' style='background-image:url("+product.picture_path+")'></div>"
                pHtml += "<div class='product-name'>"+product.productName+"</div>"
                pHtml += "<div class='button-container right-block' style='text-align:right;padding-right:15px;'>";
                pHtml += "<span class='modify' data-toggle='modal' data-target='#product-form' data-type='2' data-productid='"+product.productID+"'></span>";
                pHtml += "<span class='delete' data-productid='"+product.productID+"'></span>";
                pHtml += "</div>";
                pHtml += "</div>";
            }
            $("#production").html(pHtml);
		}, 'JSON');
    }
    
    updateProduction(25);
    
    $("#production").delegate(".delete", "click", function(){
        var r = confirm("确认删除这个产品么？");
        if(r == true){
            var btn = $(this);
            $.ajax({
                url:"productionDel",
                method:"POST",
                data:{productionID:btn.data("productid")},
                success:function(data){
                    if(data == "success"){
                        alert("成功删除产品。");
                        getUsers();
                    }
                    else if(data == "failure"){
                        alert("删除失败");
                    }
                    else{
                        alert(data);
                    }
                }
            });
        }
    });

    $('#product-form').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var type = button.data("type");
        var modal = $(this)
        modal.find(".modal-body").height(window.innerHeight - 300);
        if(type == 1){
            modal.find('.modal-title').text('新建产品');
            modal.data("productID", -1);
        }
        else{
            var pId = button.data('productid');
            modal.find('.modal-title').text('编辑产品');
            modal.data("productID", pId);
        }
    });
    
    window.onresize = function(){
        $('#product-form').find(".modal-body").height(window.innerHeight - 300);
    }
    
});
