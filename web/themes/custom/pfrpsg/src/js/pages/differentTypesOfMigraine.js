import { pfPageData } from '../utils';

(($) => {
  pfPageData('different types of migraine');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'different types of migraine | content | reference',
  );
})(jQuery);
