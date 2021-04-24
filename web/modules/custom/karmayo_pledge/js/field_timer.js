/**
* @file
*/

(function ($, Drupal) {
  Drupal.behaviors.karmayoCounterInit = {
    attach: function (context, settings) {
        $("td.timer-origin-val").each(function () {
            
            var created_original = new Date($(this).find(".timer-val").html());
             
//            var created_date = created_original.getDate();      
//            var created_month = created_original.getMonth();      
//            var created_year = created_original.getFullYear();  
//            var created_hours = created_original.getHours(); 
//            var created_minutes = created_original.getMinutes(); 
//            var created_seconds = created_original.getSeconds(); 
//          
//            var formatted_created_date = created_date + '-' + created_month + '-' + created_year+ ' ' + created_hours + ':' + created_minutes + ':' + created_seconds;
//var dt = new Date($(this).find(".timer-val").html());
           var dt = new Date($(this).find(".timer-val").html());
            dt.setHours( dt.getHours() + 24 );
           // $(this).find(".timer-val").html(formatted_created_date);
            $(this).find('.countdown-timer').countdown(dt, function(event) {
                var totalHours = event.offset.totalDays * 24 + event.offset.hours;
                $(this).html(event.strftime(totalHours + ' hr %M min %S sec'));
            });
           
        });
        $(".open").on("click", function() {
  $(".popup-overlay, .popup-content").addClass("active");
});

//removes the "active" class to .popup and .popup-content when the "Close" button is clicked 
$(".close, .popup-overlay").on("click", function() {
  $(".popup-overlay, .popup-content").removeClass("active");
});
    }
  };
})(jQuery, Drupal);

