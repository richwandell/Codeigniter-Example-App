(function($){
  /**
   * This is a simple example of loading dynamic content through AJAX.
   *
   * Full pages will be cached with forms that can be submitted through regular post form
   * submissions. When these forms are submitted there can be messages and other content
   * generated to provide feedback to the user.
   *
   * These messages are not a part of the page that we wan't cached so we load these messages
   * through AJAX as well as the csrf token that will be used for validating the form on the
   * cached page.
   */
  $(document).ready(function(){
    //Send out our ajax request to the getCsrfToken page
    $.ajax({
      url: "/car/getCsrfToken",
      dataType: 'json',
      success: function(res){
        //When we get the data back we first set our new valid token
        $("[name='"+res.csrf[0]+"']").val(res.csrf[1]);

        //Then we loop through any dynamic data and set it
        $(res.dynamic_data).each(function(i, obj){
          //This would be any HTML content that we want injected
          if(obj.data.attr === "html") {
            $(obj.selector).html(obj.data.data);

          //This would be any custom class that we want added (form validation errors etc..)
          }else if (obj.data.attr === "class"){
            $(obj.selector).addClass(obj.data.data);

          //This would be form values that we want persisted for valid form entries
          }else{
            $(obj.selector).val(obj.data.data);
          }
        });
      }
    });
  });
})(jQuery);