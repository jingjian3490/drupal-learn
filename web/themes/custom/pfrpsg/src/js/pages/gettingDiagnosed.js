import { pfPageData } from '../utils';

(($) => {
  pfPageData('getting diagnosed');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'getting diagnosed | content | reference',
  );
})(jQuery);
