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
                pHtml += "<div class='product' data-productid='"+product.productID+"'>";
                pHtml += "<div class='product-image-container' style='background-image:url("+product.pic_path+")'></div>"
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
                        var serieID = $(".series-tab.active").data("serieid");
                        updateProduction(serieID);
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
            $.post("clearSeesionData", {clear:true});
        }
        else{
            pId = button.data('productid');//编辑旧产品，获取ID
            modal.find('.modal-title').text('编辑产品');
            //请求参数，图片，特性，label，tips
            $.post(
                'feature',
                {
                    pId:pId
                },
                function(data){
                    var param = data.param;
                    var paramCon = $("#parameter");
                    for(var key in param){
                        var ele = paramCon.find("#" + key);
                        if(ele.attr("type") == "checkbox"){
                            ele.prop("checked", param[key] == "1" ? true : false);
                        }
                        else{
                            ele.val(param[key]);
                        }
                    }
                    var tipsHtml = "";
                    for(var i = 0; i < data.TipsItems.length; i++){
                        var tip = data.TipsItems[i];
                        tipsHtml += "<span class='tip-con'><span>"+tip.tips+"</span><span class='delete' data-tipsid='"+tip.tipsID+"'></span></span>"
                    }
                    $("#tag-container").html(tipsHtml);
                    
                    var featureHtml = "";
                    for(var i = 0; i < data.featureItems.length; i++){
                        var featureData = data.featureItems[i];
                        featureHtml += "<div class='feature-con'><div class='title'>"+featureData.featureTitle+"</div><div class='content'>"+featureData.featureContent+"</div><span class='delete' data-featureid='"+featureData.featureID+"'></span></div>"
                    }
                    $("#feature-container").html(featureHtml);
                                        
                    $("#productName").val(param["productName"]);
                    $("#serieID").val(param["serieID"]);
                    var picCon = $("#images-container");
                    var imageHtml = "";
                    for(key in data.pic){
                        var pic = data.pic[key];
                        imageHtml += "<div class='image-outer'><img src='"+pic.pic_path+"' class='image-responsive' /><span class='delete' data-picid='"+pic.pictureID+"'></span></div>"
                    }
                    picCon.html(imageHtml);
                }, "JSON");
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
                var pic=JSON.parse(response);
                var html = "<div class='image-outer'><img src='"+pic.pic_path+"' class='image-responsive' /><span class='delete' data-picid='"+pic.pictureID+"'></span></div>";
                $("#images-container").append(html);
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
        var obj = Object();
        obj["type"] = type;
        obj["productID"] = pId;
        obj["productName"] = $("#productName").val();
        obj["serieID"] = $("#serieID").val();
        if(obj["productName"] == ""){
            alert("请填写产品名称。");
            return ;
        }
        
        var inputs = $("#parameter input[type='text'], #parameter textarea");
        var checkboxes = $("#parameter input[type='checkbox']");
        for(var i = 0; i < inputs.length; i++){
            var inp = $(inputs[i]);
            obj[inp.attr("id")] = inp.val();
        }
        for(var i = 0; i < checkboxes.length; i++){
            var cb = $(checkboxes[i]);
            obj[cb.attr("id")] = cb.is(':checked');
        }
        
        $.post(
            'productionEdit',
            obj,
            function(data){
                var serieID = $(".series-tab.active").data("serieid");
                updateProduction(serieID);
                alert('操作成功');
                var modal = $('#product-form');
                modal.modal('hide');
            }
        );
    });

    $("#images-container").delegate(".delete", "click", function(){
        var r = confirm("确认删除这张图片么？");
        if(r == true){
            var row = $(this);
            $.ajax({
                url:"delPicture",
                method:"POST",
                data:{pictureID:row.data("picid"), productID:pId},
                success:function(data){
                    if(data == "del_success"){
                        alert("成功删除图片。");
                        row.parent().remove();
                    }
                    else{
                        alert("删除失败");
                    }
                }
            });
        }
    });
    
    $('#product-form').on('hidden.bs.modal', function (event) {
        $(".modal-body input[type='text'], .modal-body textarea").val("");
        $(".modal-body input[type='checkbox']").prop("checked",false);
        $("#images-container").html("");
        $("#tag-container").html("");
        $("#feature-container").html("");
    });
    
    $("#add-tag-bt").click(function(){
        var newTips = $("#add-tag-text").val();
        if(newTips == ""){
            return ;
        }
        $("#add-tag-text").val("");
        $.post("addTips", {
            productID: pId,
            tips: newTips
        }, function(tipData){
            $("#tag-container").append("<span class='tip-con'><span>"+tipData.tips+"</span><span class='delete' data-tipsid='"+tipData.tipsID+"'></span></span>");
        }, "JSON")
    });
    
    
    $("#tag-container").delegate(".delete", "click", function(){
        var r = confirm("确认删除这个标签么？");
        if(r == true){
            var row = $(this);
            $.ajax({
                url:"delTips",
                method:"POST",
                data:{tipsID:row.data("tipsid"), productID:pId},
                success:function(data){
                    if(data == "del_success"){
                        alert("成功删除标签。");
                        row.parent().remove();
                    }
                    else{
                        alert("删除失败");
                    }
                }
            });
        }
    });
    
    $("#add-feature-bt").click(function(){
        var featureTitle = $("#add-feature-title").val();
        var featureDescr = $("#add-feature-descr").val();
        if(featureTitle == "" || featureDescr == ""){
            return ;
        }
        $("#add-feature-title").val("");
        $("#add-feature-descr").val("");
        $.post("addFeature", {
            productID: pId,
            title: featureTitle,
            content: featureDescr
        }, function(featureData){
            $("#feature-container").append("<div class='feature-con'><div class='title'>"+featureData.featureTitle+"</div><div class='content'>"+featureData.featureContent+"</div><span class='delete' data-featureid='"+featureData.featureID+"'></span></div>");
        }, "JSON")
    });
    
    
    $("#feature-container").delegate(".delete", "click", function(){
        var r = confirm("确认删除这个特性么？");
        if(r == true){
            var row = $(this);
            $.ajax({
                url:"delFeature",
                method:"POST",
                data:{featureID:row.data("featureid"), productID:pId},
                success:function(data){
                    if(data == "del_success"){
                        alert("成功删除特性。");
                        row.parent().remove();
                    }
                    else{
                        alert("删除失败");
                    }
                }
            });
        }
    });

});
