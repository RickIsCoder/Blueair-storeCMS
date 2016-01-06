$(document).ready(function(){

  var global_scaleID = -1;

    $(document).delegate('#sceneAdd','click',function(){
      var sceneName = $('#newSceneName').val();
      $.post(
        'sceneAdd',
        {sceneName:sceneName},
        function(data){
          if(data='success'){
            alert('success,请刷新');
          }
        }
      )
    });

    $(document).delegate('.sceneDel','click',function(){
      var sceneID = $(this).parents('.scenelist').attr('sceneID');
      $(this).parents('.scenelist').remove();
      $.post(
        'sceneDel',
        {sceneID:sceneID},
        function(data){

        }
      )
    });

    $(document).delegate('.sceneEdit','click',function(){
      var sceneID = $(this).parents('.scenelist').attr('sceneID');
      var sceneName = $(this).parents('.scenelist').children('.sceneName').val();
      $.post(
        'sceneEdit',
        {sceneID:sceneID,sceneName:sceneName},
        function(data){
          alert(data);
        }
      )
    });


    $(document).delegate('.scaleAdd','click',function(){
      var sceneID = $(this).parents('.scenelist').attr('sceneID');
      var scaleName = $(this).parents('.scenelist').children('.newScaleName').val();

      $.post(
        'scaleAdd',
        {sceneID:sceneID,scaleName:scaleName},
        function(data){
          alert(data);
        }
      )
    });

    $(document).delegate('.scaleDel','click',function(){
      var scaleID = $(this).parents('.scale').attr('scaleID');
      $(this).parents('.scale').remove();
      $.post(
        'scaleDel',
        {scaleID:scaleID},
        function(data){
          if (data!='success') {
            alert('error');
          };
        }
      )
    });

    $(document).delegate('.scaleEdit','click',function(){
      var scaleID = $(this).parents('.scale').attr('scaleID');
      var scaleName = $(this).parents('.scale').children('.scaleName').val();
      $.post(
        'scaleEdit',
        {scaleID:scaleID,scaleName:scaleName},
        function(data){
          alert(data);
        }
      )
    });


    $(document).delegate('.productionDel','click',function(){
      var productionID = $(this).parents('.production').attr('productionID');
      $(this).parents('.production').remove();
      $.post(
        'sceneProductionDel',
        {productionID:productionID},
        function(data){
          if(data!='success'){
            alert('error');
          }
        }
      )
    });

    $(document).delegate('.productionAdd','click',function(){
      var scaleID = $(this).parents('.scale').attr('scaleID');
      global_scaleID = scaleID;
      $.post(
        'sceneProductionCheck',
        {scaleID:scaleID},
        function(data){
          var data=JSON.parse(data);
          for (var i = data.length - 1; i >= 0; i--) {
            var html = '';

            html += "<div class='choice' productionID ='";
            html += data[i]['productID'];
            html += "'>";
            html += data[i]['productName'];
            html += "<button class='productionAddAction'>添加产品</button></div>";
            $('#choicelist').append(html);
          };
        }
      )
    });

    $(document).delegate('.productionAddAction','click',function(){
      var productionID = $(this).parents('.choice').attr('productionID');
      $(this).parents('.choice').remove();
      $.post(
        'sceneProductionAdd',
        {productionID:productionID,scaleID:global_scaleID},
        function(data){
          if(data!='success'){
            alert('error');
          }
        }
      )
    });










});