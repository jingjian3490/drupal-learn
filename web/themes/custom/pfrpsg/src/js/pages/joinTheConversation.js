import { pfPageData, pfAnalyticsAttrData } from '../utils';

(($) => {
  pfPageData('join the conversation');
  const $front = $('.front');
  const $back = $('.back');
  let $lastFlip;
  $front.on('click', function () {
    $(this).parent().addClass('fliping');
    if ($lastFlip && $lastFlip.length > 0) {
      $lastFlip.parent().removeClass('fliping');
    }
    $lastFlip = $(this);
    const name = $(this)
      .parent()
      .find('.field--name-field-title')
      .text()
      .toLowerCase();
    pfAnalyticsAttrData(`join the conversation | content | ${name}`);
  });
  $back.on('click', function () {
    // fliping
    $(this).parent().removeClass('fliping');
    $lastFlip = null;
  });
})(jQuery);
