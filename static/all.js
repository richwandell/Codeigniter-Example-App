(function($){
  $(document).ready(function(){
    $.ajax({
      url: "/car/getCsrfToken",
      dataType: 'json',
      success: function(res){
        $("[name='"+res.csrf[0]+"']").val(res.csrf[1]);
        $(res.dynamic_data).each(function(i, obj){
          if(obj.data.attr === "html") {
            $(obj.selector).html(obj.data.data);
          }else if (obj.data.attr === "class"){
            $(obj.selector).addClass(obj.data.data);
          }else{
            $(obj.selector).val(obj.data.data);
          }
        });
      }
    });
  });
})(jQuery);