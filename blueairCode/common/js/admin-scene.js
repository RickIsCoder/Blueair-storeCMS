$(function(){
    var sceneData = [];
    function getSceneData(){
        $.ajax({
            url:"sceneData",
            method:"POST",
            dataType:"JSON",
            success:function(data){
                var appendHtml = '';
                appendHtml += '<table>';
                for(var i = 0; i < data['sceneList'].length; i++){
                    var scene = data['sceneList'][i];
                    appendHtml += '<tr>';
                    appendHtml += '<td width="100" rowspan="' + (scene.scale.length+1) + '"><img class="icon-con img-responsive" src="'+scene.scenePic_path+'"></td>';
                    appendHtml += '<td width="100" class="formCenter" rowspan="' + (scene.scale.length+1) + '">';
                    appendHtml += scene.sceneName;
                    appendHtml += '<div class="operation" data-id=' + scene.sceneID + ">"
                    appendHtml += '<span class="modify editIcon" data-toggle="modal" data-target="#scene-form" data-type="2"></span>';
                    appendHtml += '<span class="delete dropIcon"></span>';
                    appendHtml += '</div>';
                    appendHtml += '</td>';
                    appendHtml += '</tr>';
                    
                    for (var i1 = 0; i1 < scene.scale.length; i1++){
                        appendHtml += '<tr>';
                        appendHtml += '<td class="formCenter">'+scene.scale[i1].scaleName+'</td>';
                        appendHtml += '<td>';
                        for(var i2 = 0; i2 < scene.scale[i1].production.length; i2++ ){
                            appendHtml += "<span class='scene-product'>" + scene.scale[i1].production[i2].productName;
                            appendHtml += '<span class="delete delete-product" data-id="'+scene.scale[i1].production[i2].sceneProductID+'"></span></span>';
                        }
                        appendHtml += '</td>';
                        appendHtml += '<td class="form-inline" width="160">';
                        if(scene.scale[i1].noExistProdution.length > 0){
                            appendHtml += '<select class="form-control" style="width:75px;">';

                            for(var noti = 0; noti < scene.scale[i1].noExistProdution.length; noti++){
                                var notPro = scene.scale[i1].noExistProdution[noti];
                                appendHtml += "<option value='"+notPro.productID+"'>"+notPro.productName+"</option>"
                            }
                            appendHtml += '</select><button style="margin-left: 8px;" class="btn btn-primary add-new-product" data-scaleid="'+scene.scale[i1].scaleID+'">添加</button>';
                        }
                        appendHtml += '</td>';
                        appendHtml += '</tr>';
                        
                    }
                   
                }
                appendHtml += '</table>';

                $("#sense-table-body").html(appendHtml);
            }
        });
    }
    
    $("#sense-table-body").delegate(".add-new-product", "click", function(){
        var productionID = $("select", $(this).parent()).val();
        var scaleID = $(this).data("scaleid");
        $.post("addSceneProduction", {
            productionID:productionID,
            scaleID: scaleID
        }, function(){
            getSceneData();
        });
    });
    
    getSceneData();
    
    var sceneId = -1;
    
    $('#scene-form').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        type = button.data("type");
        var modal = $(this);
        if(type == 1){
            sceneId = -1;
            
            $("#scene-name").val("");
            $("#images-container").html("");
            $("#scale-container").html("");
        }
        else{
            sceneId = button.parent(".operation").data("id");
            $.post("sceneInfo", {sceneID:sceneId}, function(data){
                $("#scene-name").val(data.sceneName);
                var html = "<div class='image-outer'><img src='"+data.scenePic_path+"' class='img-responsive' /></div>";
                $("#images-container").html(html);
                
                var scaleHtml = "";
                for(var i = 0; i < data.scale.length; i++){
                    var scale = data.scale[i];
                    scaleHtml += "<span class='scale-con'><span class='scale-name'>"+scale.scaleName+"</span><span class='delete' data-scaleid='"+scale.scaleID+"'></span></span>"
                }
                $("#scale-container").html(scaleHtml);
                
            }, "JSON");
        }
                // 创建一个上传参数
        var uploadOption=
        {
            // 提交目标
            action: 'uploadIcon',
            // 服务端接收的名称
            name: "file",
            // 参数
            data: {sceneID:sceneId},

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
                var html = "<div class='image-outer'><img src='"+response+"' class='img-responsive' /></div>";
                $("#images-container").html(html);
            }
        }

        // 初始化图片上传框
        var oAjaxUpload = new AjaxUpload('#add-scene-icon', uploadOption);
    });
    
    $("#sense-table-body").delegate(".delete-product", "click", function(){
        var r = confirm("确认删除这个产品么？");
        if(r == true){
            var row = $(this);
            $.ajax({
                url:"delSceneProduction",
                method:"POST",
                data:{productionID:row.data("id")},
                success:function(data){
                    if(data == "1"){
                        alert("成功删除产品。");
                        getSceneData();
                    }
                    else if(data == "0"){
                        alert("删除失败");
                    }
                    else{
                        alert(data);
                    }
                }
            });
        }
    });
    
    $("#scene-form").delegate(".delete", "click", function(){
       
        var r = confirm("确认删除这个规模么？");
        if(r==true){
            var row = $(this);
            $.ajax({
                url:"delScale",
                method:"POST",
                data:{scaleID:row.data("scaleid")},
                success:function(data){
                    if(data == "success"){
                       alert("操作成功");
                        row.parent().remove();
                    }else{
                        alert("操作失败");
                    }
                       
                }
                   
                
            });
        }
        
        
    });
    
    $("#buttonSave").click(function(){
        var sceneName = $("#scene-name").val();
        if(sceneName != ""){
            $.post("editScene", {
                sceneID:sceneId,
                sceneName: sceneName
            }, function(){
                $("#scene-form").modal('hide');
                getSceneData();
            });
        }
    });

    $("#sense-table-body").delegate(".dropIcon", "click", function(){
        var r = confirm("确认删除这个场景么？");
        if(r == true){
            var row = $(this).parent(".operation");
            $.ajax({
                url:"delScene",
                method:"POST",
                data:{sceneID:row.data("id")},
                success:function(data){
                    if(data == "1"){
                        alert("成功删除场景。");
                        getSceneData();
                    }
                    else if(data == "0"){
                        alert("删除失败");
                    }
                    else{
                        alert(data);
                    }
                }
            });
        }
    });
    
    $("#add-new-scale").click(function(){
        var describe = $("#new-scale-name").val();
        if(describe != ''){
            $("#new-scale-name").val('');
            $.post("addScale", {
                sceneID: sceneId,
                scale:describe
            }, function(data){
                var scaleHtml = "<span class='scale-con'><span class='scale-name'>"+describe+"</span><span class='delete' data-scaleid='"+data+"'></span></span>";
                $("#scale-container").append(scaleHtml);
            });
        }

    })
    
});
