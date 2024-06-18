import { pfPageData, pfAnalyticsAttrData } from '../utils';

(($) => {
  pfPageData('myths vs facts');
  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'myths vs facts | content | reference',
  );

  const $myths = $('.field--name-field-myth');
  const $factsLink = $('.field--name-field-fact a');
  $factsLink.each(function () {
    const text = $(this).attr('href').slice(1).split('-').join(' ');
    $(this).attr('sc:linkname', `myths vs facts | content | ${text}`);
  });
  $myths.on('click', function () {
    const $fact = $(this).siblings('.field--name-field-fact');
    $(this).toggleClass('fact-expand');
    $fact.stop().slideToggle();
    if ($(this).hasClass('fact-expand')) {
      const text = $(this).find('.title-des').text();
      pfAnalyticsAttrData(`myths vs facts | content | ${text}`);
    }
  });
  const $firstMyth = $myths.eq(0);
  const $firstFact = $firstMyth.siblings('.field--name-field-fact');
  $firstMyth.toggleClass('fact-expand');
  $firstFact.stop().slideToggle();
})(jQuery);
