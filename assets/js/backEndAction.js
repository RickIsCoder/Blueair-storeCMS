/* global $ */
$(function(){
    
    $('.seriesWrapper').click(function(event){
        var currentItem = $(event.currentTarget);
        if (!currentItem.hasClass('currentSeries')) {
            $('.seriesWrapper').removeClass('currentSeries');
            currentItem.addClass('currentSeries');
            
            var productsContainer = $('.productsContainer');
            productsContainer.removeClass('currentSeries');
            for (var index = 0; index <= productsContainer.length; index++) {
                if ($(productsContainer[index]).data('seriesid') === currentItem.data('seriesid')) {
                    $(productsContainer[index]).addClass('currentSeries');
                }
            }
        }    
    });
    
    $('#editSeriesBtn').click(function(event){
        
    });
    
});