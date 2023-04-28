jQuery(function ($) {
  $('#publish, #save-post').on('click.require-post-category', function (e) {
    var boolReturn = false;
    if ( $( 'input[name="apple_news_sections[]"]:checked' ).length < 2 ) {
      boolReturn = false;
    } else {
      $( 'input[name="apple_news_sections[]"]:checked' ).each(function() {
        var labelElement = $("label[for='" + $(this).attr('id') + "']");
        if( 'Main' == labelElement.text() ) {
          boolReturn =  true;
        }
      });
    }
    if ( ! boolReturn ) {
      alert( 'Please choose appropriate sections for Apple News, "Main" has to be one of them.' );
    }
    return boolReturn;
  });
});
