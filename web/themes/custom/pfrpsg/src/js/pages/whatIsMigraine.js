import { pfPageData } from '../utils';

(($) => {
  pfPageData('what is migraine');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'what is migraine | content | reference',
  );
})(jQuery);
