import { pfAnalyticsAttrData } from '../utils';

(($) => {
  const $floatIcon = $('#block-pfrpsg-assess-mycondition-floating-icon a');
  if ($floatIcon.length > 0) {
    $floatIcon.attr(
      'sc:linkname',
      'global content | navigation | assess my condition',
    );
  }
  // external popup
  function cancelPopup() {
    const $externalCancelBtn = $(
      '.external-link-popup-content+.ui-dialog-buttonpane .ui-dialog-buttonset button:last-child',
    );
    $externalCancelBtn.trigger('click');
  }
  const $body = $('body');
  $body
    .on('click', '.external-link-popup-content', function (e) {
      const outerWidth = $(this).outerWidth();
      const offsetLeft = outerWidth - 25 - 39;
      const offsetRight = outerWidth - 25;
      const xIn = e.offsetX >= offsetLeft && e.offsetX <= offsetRight;
      const yIn = e.offsetY >= 22 && e.offsetY <= 59;
      if (xIn && yIn) {
        cancelPopup();
      }
    })
    .on('mousedown', '.ui-dialog-buttonset button', function () {
      const str = $(this).text();
      pfAnalyticsAttrData(
        `global | content | external popup | ${str}`,
        'external',
      );
    });
})(jQuery);
