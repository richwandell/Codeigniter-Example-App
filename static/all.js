(function($){
  $(document).ready(function(){
    $.ajax({
      url: "/car/getCsrfToken",
      dataType: 'json',
      success: function(res){
        $("[name='"+res.csrf_token[0]+"']").val(res.csrf_token[1]);
        if($.trim(res.flash.message) !== "") {
          $("#flash_message").html(res.flash.message);
        }
        if($.trim(res.flash.error) !== "") {
          $("#flash_error").html(res.flash.error);
        }
      }
    });
  });
})(jQuery);