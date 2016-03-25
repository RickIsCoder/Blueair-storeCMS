$(document).ready(function(){
    $(document).delegate('.serie-del-btn','click',function(){
      var serieID = $(this).parents('.serie').attr('serieID');
      $(this).parents('.serie').remove();
      $.post(
        'serieDel',
        {serieID:serieID},
        function(data){
          if(data!='success'){
            alert('error');
          }
        }
      )
    });

    $(document).delegate('.production-del-btn','click',function(){
      var productionID = $(this).parents('.production').attr('productionID');
      $(this).parents('.production').remove();
      $.post(
        'productionDel',
        {productionID:productionID},
        function(data){
          if(data!='success'){
            alert('error');
          }
        }
      )
    });

    $(document).delegate('.serie-add-btn','click',function(){
      var serieName = $('#serieName').val();
      $.post(
        'serieAdd',
        {serieName:serieName},
        function(data){
          var html ='';
          html += '<div class="serie" serieID= ';
          html += data ;
          html += '>';
          html += '<table>';
          html += '<th>';
          html += serieName;
          html += '<button class="serie-del-btn">';
          html += '删除系列';
          html += '</button>';
          html += '</th>';
          html += '</table>';
          html += '</div>';
          $('#addSerie').before(html);
        }
      )
    });
});