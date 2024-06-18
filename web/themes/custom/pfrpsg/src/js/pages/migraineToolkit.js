import { pfPageData, pfAnalyticsAttrData } from '../utils';

(($) => {
  pfPageData('migraine toolkit');
  const $downloadBtn = $('.paragraph--type--toolkit .button');
  const $downloadFile = $('.field--name-field-toolkit-file a');
  $downloadBtn.on('click', function () {
    pfAnalyticsAttrData(
      'migraine toolkit | content | download migraine toolkit',
    );
    const url = $downloadFile.attr('href');
    $downloadFile.click();
    const a = document.createElement('a');
    a.download = $downloadFile.text();
    a.href = url;
    document.body.appendChild(a);
    a.click();
    a.remove();
  });
})(jQuery);
