$(function (){

    var productID = $('#feature').attr('productionID');
    var path = "../";

    //添加一条特性
    $('#feature-add-btn').click(function(){
        var f_title = $('#new-featureTitle').val();
        var f_content = $('#new-featureContent').val();

        $.post(
            path+"addFeature",
            {
                productID:productID,
                title:f_title,
                content:f_content,
            },
            function(data){
                var html = '';
                html += '<div class="feature-item" featureID="'+data+'">';
                html += '特性名称:<input type="text" value="'+f_title+'">';
                html += '特性描述:<input type="text" value="'+f_content+'">';
                html +='<button class="feature-del-btn">删除特性</button><button class="feature-edit-btn">修改特性</button></div>';   
                $('#feature-content').append(html);
            }
        );
    });


    //del 特性
    $(document).delegate('.feature-del-btn','click',function(){
        var featureID = $(this).closest('.feature-item').attr('featureID');
        $(this).closest('.feature-item').remove();
        $.post(
            path+"delFeature",
            {featureID:featureID},
            function(data){
                if(data != 'success'){
                    alert('ERROR!');
                }
            }
        );       
    });

    //edit 特性
    $('.feature-edit-btn').click(function(){
        var featureID = $(this).closest('.feature-item').attr('featureID');
        var featureTitle = $(this).parent().children('.featureTitle').val();
        var featureContent = $(this).parent().children('.featureContent').val();
        $.post(
            path+"editFeature",
            {
                featureID:featureID,
                featureTitle:featureTitle,
                featureContent:featureContent
            },
            function(data){
                if(data != 'success'){
                    alert('ERROR!');
                }
            }
        );
    });

    //edit 描述
    $('#des-edit-btn').click(function(){
        var des = $('#des').val();
        $.post(
            path+"editDescription",
            {productID:productID,des:des},
            function(data){
                if(data != 'success'){
                    alert('ERROR!');
                }
            }
            );
    });

    //label 
    $('#label-edit-btn').click(function(){
        var label = $('#label').val();
        $.post(
            path+"editLabel",
            {productID:productID,label:label},
            function(data){
                if(data != 'success'){
                    alert('ERROR!');
                }
            }
            );
    });

    //添加tips
    $('#tips-add-btn').click(function(){
        var tips = $('#new-tips').val();

        $.post(
            path+"addTips",
            {
                productID:productID,
                tips:tips
            },
            function(data){
                var html = '';
                html += '<div class="tips-item" tipsID="'+data+'">';
                html += '特性名称:<input type="text" value="'+tips+'">';
                html +='<button class="tips-del-btn">删除特性</button><button class="tips-edit-btn">修改特性</button></div>';   
                $('#tips-content').append(html);
            }
        );
    });


    //del tips
    $(document).delegate('.tips-del-btn','click',function(){
        var tipsID = $(this).closest('.tips-item').attr('tipsID');
        $(this).closest('.tips-item').remove();
        $.post(
            path+"delTips",
            {tipsID:tipsID},
            function(data){
                if(data != 'success'){
                    alert('ERROR!');
                }
            }
        );       
    });

    //edit tips
    $('.tips-edit-btn').click(function(){
        var tipsID = $(this).closest('.tips-item').attr('tipsID');
        var tips = $(this).parent().children('.tips').val();
        $.post(
            path+"editTips",
            {
                tips:tips,
                tipsID:tipsID
            },
            function(data){
                if(data != 'success'){
                    alert('ERROR!');
                }
            }
        );
    });




});