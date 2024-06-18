import { pfPageData } from '../utils';

(($) => {
  pfPageData('migraine or headache');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'migraine or headache | content | reference',
  );
  const $contentLink = $('.field--name-field-text-card a');
  $contentLink.each(function () {
    const text = $(this).attr('href').slice(1).split('-').join(' ');
    $(this).attr('sc:linkname', `migraine or headache | content | ${text}`);
  });
})(jQuery);
