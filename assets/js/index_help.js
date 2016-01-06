$(document).ready(function(){

    $(document).delegate('.sceneOption','click',function(){
      var sceneID = $(this).attr('sceneID');
      global_sceneID = sceneID;
      $.post(
        'getScale',
        {sceneID:sceneID},
        function(data){
          var data=JSON.parse(data);
          for (var i = data.length - 1; i >= 0; i--) {
            var html = '';
            html += "<div class='scale' scaleID ='";
            html += data[i]['scaleID'];
            html += "'>";
            html += data[i]['scaleName'];
            html += "<button class='advice'>查看推荐</button></div>";
            $('#scale').append(html);
          };
        }
      )
    });

    $(document).delegate('.advice','click',function(){
      var scaleID = $(this).parents('.scale').attr('scaleID');

      $.post(
        'advice',
        {scaleID:scaleID},
        function(data){
          var data=JSON.parse(data);
          data = data['production'];
          for (var i = data.length - 1; i >= 0; i--) {
            var html = '<br>';
            html += data[i]['productName'];

            $('#advice').append(html);
          };
        }
      )
    });










});