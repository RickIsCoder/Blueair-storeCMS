$(function () {
    var sceneData = null;

    $.getJSON("getAllSceneInfo", function (data) {
        sceneData = data;

        var sceneHtml = "";
        for (var i = 0; i < sceneData.length; i++) {
            var scene = sceneData[i];
            sceneHtml += "<div data-sceneindex='" + i + "' class='scene-item" + (i == 0 ? " active" : "") + "'><img src='" + scene.scenePic_path + "' class='scene-image' /><div class='scene-name'>" + scene.sceneName + "</div></div>";
        }
        $("#scene-container").html(sceneHtml);

        var scaleHtml = "";
        for (var j = 0; j < sceneData[0].scale.length; j++) {
            scaleHtml += "<div data-scaleindex='" + j + "' class='scale-item" + (j == 0 ? " active" : "") + "'>" + sceneData[0].scale[j].scaleName + "</div>";
        }
        $("#scale-container").html(scaleHtml);
    });

    $("#scene-container").delegate(".scene-item", "click", function () {
        var sceneindex = $(this).data("sceneindex");
        $(".scene-item").removeClass("active");
        $(this).addClass("active");
        var scaleHtml = "";
        for (var j = 0; j < sceneData[sceneindex].scale.length; j++) {
            scaleHtml += "<div data-scaleindex='" + j + "' class='scale-item" + (j == 0 ? " active" : "") + "'>" + sceneData[sceneindex].scale[j].scaleName + "</div>";
        }
        $("#scale-container").html(scaleHtml);
    });

    $("#scale-container").delegate(".scale-item", "click", function () {
        $(".scale-item").removeClass("active");
        $(this).addClass("active");
    });

    $("#add-scale-btn").click(function () {
        var scene = $(".scene-item.active");
        var scale = $(".scale-item.active");
        $("#myrooms-container").append("<div data-sceneindex='" + scene.data("sceneindex") + "' data-scaleindex='" + scale.data("scaleindex") + "' class='myroom-item'>" + scene.find(".scene-name").text() + "(" + scale.text() + ")<span class='delete'></span></div>");
    });

    $("#myrooms-container").delegate(".delete", "click", function () {
        $(this).parent().remove();
    });

    $("#view-plan-btn").click(function () {
        var myrooms = "";
        $("#myrooms-container .myroom-item").each(function (index, ele) {
            var scene = sceneData[$(ele).data("sceneindex")];
            var scale = scene.scale[$(ele).data("scaleindex")];
            myrooms += "<tr>";
            myrooms += "<td>";
            myrooms += "<div class='scene-item'><img src='" + scene.scenePic_path + "' class='scene-image' /><div class='scene-name'>" + scene.sceneName + "(" + scale.scaleName + ")</div></div>";
            myrooms += "</td>";
            myrooms += "<td>";
            for (var i = 0; i < scale.productions.length; i++) {
                myrooms += "<div class='scene-item product-image' data-productid='" + scale.productions[i].productID + "'><img src='" + scale.productions[i].pic_path + "' class='scene-image' /><div class='scene-name'>" + scale.productions[i].productName + "</div></div>";
            }
            myrooms += "</td>";
            myrooms += "</tr>";
        });
        $("#result-table-body").html(myrooms);
        $("#choose-result").animate({
            height: "100%"
        }).addClass("active");
    });
    $("#replan-btn").click(function () {
        $("#choose-result").animate({
            height: "0"
        }, function () {
            $("#result-table-body").html("");
        }).removeClass("active");
    });
    $(".menu-item").click(function () {
        $(".menu-item").removeClass("active");
        $(this).addClass("active");
        $(".content-container.active").fadeOut(300).removeClass("active");
        $($(this).data("panel")).addClass("active").fadeIn(500);
    });
    
    $("body").delegate(".product-image", "click", function(){
        window.open("productDetail.html?productID=" + $(this).data("productid"));
    });


    $("#customer-submit").click(function () {
        var companyName = $("#companyName").val();
        var Email = $("#Email").val();
        var tel = $("#tel").val();
        var contacts = $("#contacts").val();
        var address = $("#address").val();
        var describe = $("#describe").val();
        $.ajax({
            url: "sendEmail",
            method: "POST",
            data: {
                companyName: companyName,
                Email: Email,
                tel: tel,
                contacts: contacts,
                address: address,
                describe: describe,
            },
            success: function (data) {
                if (data == true) {
                    alert("提交成功");

                } else {
                    alert("error");
                }

            }
        });
    });
    
    
    $(".nav-style").click(function () {
        $(".nav-style").removeClass("click-nav");
        $(this).addClass("click-nav");
    });

    
    $(".table-content").hide();
    $("#nav-one").show();
    
    $("#room-one").click(function(){
        $(".table-content").hide();
        $("#nav-one").show();
    });
    
    $("#room-two").click(function(){
        $(".table-content").hide();
        $("#nav-two").show();
    });
    
    $("#room-three").click(function(){
        $(".table-content").hide();
        $("#nav-three").show();
    });


  
    $("#room-four").click(function () {
        $(".table-content").hide();
        $("#nav-four").show();

    });
        $("#room-five").click(function(){
        $(".table-content").hide();
        $("#nav-five").show();
    });


});
