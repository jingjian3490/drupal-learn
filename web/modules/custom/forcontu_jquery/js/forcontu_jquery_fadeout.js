(function ($) {
  'use strict';

  $(document).ready(function () {
    $('.fadeout').delay(2000).fadeOut(3000);
  });

  console.log(drupalSettings.forcontu_jquery);

  $('#block1').css('border', '3px solid red');

  $('#ocultar').click(function (event) {
    event.preventDefault();
    $('#box1').fadeOut(2000);
  });
  $('#mostrar').click(function (event) {
    event.preventDefault();
    $('#box1').slideDown(3000);
  });
})(jQuery);
