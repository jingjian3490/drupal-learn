import { pfPageData } from '../utils';

(($) => {
  pfPageData('migraine triggers');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'migraine triggers | content | reference',
  );
})(jQuery);
