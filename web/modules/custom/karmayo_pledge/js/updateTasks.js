/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



(function($) {
      //$(document).ready(function() {
	//$('.path-my-tasks').attr('id','path-my-tasks')	  
    $('.update-task-to-perform').click(function (e) {
      e.preventDefault();
      var kid = $(this).next('.kid-value').html();
      if (confirm('Are you sure you want to perform this?')) {
          $.ajax({
              url: '/karmayo/update-to-perform/' + kid,
              type: "GET",
              data: {
                'id': kid
              },
              success: function (data) {
                $("#user-pending-tasks-list").load(location.href + " #user-pending-tasks-list");
				//$("#block-monthlyleaderboardblock").load(location.href + " #block-monthlyleaderboardblock");
              }
          });
      }
});
     // }) 
      
      

  })(jQuery);