import { pfPageData } from '../utils';

(($) => {
  pfPageData('lifestyle tips');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'lifestyle tips | content | reference',
  );
})(jQuery);
