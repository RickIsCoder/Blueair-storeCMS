$(function ()
{
    var productID = $('#productID').attr('productID');
    //新增和修改，path 相差一个 “admin”
    var path = '../';
    if (productID == ''){
        path = '';
    }

    //每次编辑室clear上一次的session痕迹
    if(productID == ''){
        $.post(
            path+"clear",
            {
            clear:true
            }
            ,
            function(data){
            }
        ); 
    }

    // 创建一个上传参数
    var uploadOption =
    {
        // 提交目标
        action: path+'admin/pic/upload',
        // 服务端接收的名称
        name: "file",
        // 参数
        data: {productID:productID},

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
            var html='';
            html+='<div class="pic-wall" picid="';
            html+=response.pictureID;
            html+='" style="background-image:url(';
            html+=path+'upload/';
            html+=response.pic_path;
            html+=');"><button class="btn_del">del_pic</button></div>';
            $("#pic_selector").before(html);
        }
    }

    // 初始化图片上传框
    var oAjaxUpload = new AjaxUpload('#pic_selector', uploadOption);

    //delete picture
    $(document).delegate('.btn_del','click',function(){
        var pictureID = $(this).closest('.pic-wall').attr('picid');
        $(this).closest('.pic-wall').remove();
        $.post(
            path+'delPicture',
            {pictureID:pictureID,productID:productID},
            function(data){
                if(data != 'del_success'){
                    alert('ERROR!');
                }
            }
        );       
    });

    //submit form
    $('#submit').click(function(){
        if ($('#serieID').val() ==null)
        { 
            alert('至少添加一个系列');
            return ;
        }

        $.post(
            path+"ap",
            {
                productID:productID,
                serieID:$('#serieID').val(),
                productName:$('#productName').val(),
                producting_area:$('#producting_area').val(),
                material:$('#material').val(),
                warranty:$('#warranty').val(),
                reg_warranty:$('#reg_warranty').val(),
                height:$('#height').val(),
                width:$('#width').val(),
                thickness:$('#thickness').val(),
                weight:$('#weight').val(),
                color:$('#color').val(),
                power_line_length:$('#power_line_length').val(),
                wheel:$('#wheel').val(),
                filter_life:$('#filter_life').val(),
                smoke:$('#smoke').val(),
                dirt:$('#dirt').val(),
                pollen:$('#pollen').val(),
                area:$('#area').val(),
                wind_gears:$('#wind_gears').val(),
                wind_quantity:$('#wind_quantity').val(),
                gas_exchange:$('#gas_exchange').val(),
                power_consume:$('#power_consume').val(),
                noise:$('#noise').val(),
                remote_control:$('input[name="remote_control"]:checked').val(),
                particle_sensor:$('input[name="particle_sensor"]:checked').val(),
                gas_sensor:$('input[name="gas_sensor"]:checked').val(),
                fan_speed_control:$('input[name="fan_speed_control"]:checked').val(),
                filter:$('input[name="filter"]:checked').val(),
                timer:$('input[name="timer"]:checked').val(),
                motion_seneor:$('input[name="motion_seneor"]:checked').val(),
                armorplate_glass_displayer:$('input[name="armorplate_glass_displayer"]:checked').val(),
                HEPASilentPlus:$('input[name="HEPASilentPlus"]:checked').val(),
                inner_filter:$('#inner_filter').val(),
                append:$('#append').val(),
                accessories:$('#accessories').val(),
            },
            function(data){
                location.href = path+"apc";
            }
        ); 


    });

});