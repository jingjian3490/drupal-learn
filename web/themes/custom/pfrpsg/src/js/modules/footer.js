(($) => {
  const $footerLink = $('#block-pfrpsg-footer-link a');
  const $followLink = $('#block-pfrpsg-follow-us a');
  const $footerAssess = $('#block-pfrpsg-assess-my-condition-footer a');
  const $footerContentLink = $('#block-pfrpsg-footer-copyright a');

  // set custom tracking
  $footerAssess.attr(
    'sc:linkname',
    'global footer | navigation | assess my condition',
  );
  $footerLink.each(function () {
    const menuText = $(this).text().toLowerCase();
    $(this).attr('sc:linkname', `global footer | navigation | ${menuText}`);
  });
  $followLink.each(function () {
    const text = $(this).find('img').attr('alt').split('.')[0].split('-')[0];
    $(this).attr('sc:linkname', `global footer | navigation | ${text}`);
  });
  $footerContentLink.each(function () {
    const text = $(this).text().toLowerCase();
    $(this).attr('sc:linkname', `global footer | copyright | ${text}`);
  });
})(jQuery);
