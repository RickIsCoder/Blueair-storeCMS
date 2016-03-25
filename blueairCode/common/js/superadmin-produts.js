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

    var type,pId;//type,pId 声明为全局，方便'#user-submit'调用

    $('#product-form').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        type = button.data("type");
        var modal = $(this)
        modal.find(".modal-body").height(window.innerHeight - 300);
        if(type == 1){
            pId = -1;//添加新产品，ID设为-1
            modal.find('.modal-title').text('新建产品');
            modal.data("productID", -1);
        }
        else{
            pId = button.data('productid');//编辑旧产品，获取ID
            modal.find('.modal-title').text('编辑产品');
            modal.data("productID", pId);
            //请求参数，图片，特性，label，tips
            $.post(
                'feature',
                {
                    pId:pId
                },
                function(data){
                    var data=JSON.parse(data);
                }
            );
        }

        // 创建一个上传参数
        var uploadOption=
        {
            // 提交目标
            action: 'upload',
            // 服务端接收的名称
            name: "file",
            // 参数
            data: {productID:pId},

            dataType: 'JSON',
            // 自动提交
            autoSubmit: true,
            // 选择文件之后…
            onChange: function (file, extension) {
                if (new RegExp(/(jpg)|(jpeg)|(bmp)|(gif)|(png)/i).test(extension)) {
                    $("#filepath").val(file);
                } else {
                    alert("只限上传图片文件，请重新选择！");
                }
            },
            // 开始上传文件
            onSubmit: function (file, extension) {
            },
            // 上传完成之后
            onComplete: function (file,response) {
                var response=JSON.parse(response);
                // var html='';
                // $("#pic_selector").before(response);
            }
        }

        // 初始化图片上传框
        var oAjaxUpload = new AjaxUpload('#pic_selector', uploadOption);

    });
    
    window.onresize = function(){
        $('#product-form').find(".modal-body").height(window.innerHeight - 300);
    }

    // 添加，编辑(参数)
    $('#user-submit').click(function(){
        $.post(
            'productionEdit',
            {
                type:type
                /*
                    参数list
                */
            },
            function(data){
                console.log(data);
                //成功则清空上一次未保存的session信息
                $.post('clearSeesionData',{clear:true},function(data){
                });

            }
        );
    });









});
