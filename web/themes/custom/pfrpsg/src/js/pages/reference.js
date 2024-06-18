import { pfPageData } from '../utils';

(($) => {
  pfPageData('reference');
  const curHash = document.location.hash;
  function handleTarget(hash) {
    const target = document.getElementById(hash.slice(1));
    if (!target) {
      return;
    }
    let offset = 100;
    if ($('body').hasClass('toolbar-tray-open')) {
      offset += 39;
    }
    if ($('body').hasClass('toolbar-fixed')) {
      offset += 39;
    }
    const targetOffset = $(target).offset().top - offset;
    $('html,body').animate({ scrollTop: targetOffset }, 400);
  }

  if ('scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual';
  }
  handleTarget(curHash);
})(jQuery);
