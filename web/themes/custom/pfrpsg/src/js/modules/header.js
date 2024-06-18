(($) => {
  const $header = $('header');
  const $body = $('body');
  const $menuLink = $('.menu-item > a');
  const $homeLink = $('.home-icon-mb');
  const $headerAssess = $('#block-pfrpsg-assess-mycondition a');
  let scrollTop = $(window).attr('scrollY');
  $(window).scroll(function () {
    scrollTop = $(this).attr('scrollY');
    if ($body.css('position') === 'fixed') {
      scrollTop = -parseInt($body.css('top'), 10);
    }
  });

  // set custom tracking
  $homeLink.attr('sc:linkname', 'global header | navigation | home');
  $headerAssess.attr(
    'sc:linkname',
    'global header | navigation | assess my condition',
  );
  if ($body.hasClass('path-quiz-result')) {
    $headerAssess.attr('href', '/find-a-doctor');
    $headerAssess.text('Find a Doctor');
    $headerAssess.attr(
      'sc:linkname',
      'global header | navigation | find a doctor',
    );
  }
  $menuLink.each(function () {
    const menuText = $(this).text().toLowerCase().split('-').join(' ');
    $(this).attr('sc:linkname', `global header | navigation | ${menuText}`);
  });

  // Prevent background scrolling when popup is shown
  function stopScroll() {
    $body.css('position', 'fixed');
    $body.css('width', '100%');
    $body.css('top', `${-1 * scrollTop}px`);
    $body.css('overflow-y', 'scroll');
  }
  // Restore background scrolling when popup is hidden
  function recoverScroll() {
    $body.css('position', 'static');
    $body.css('width', 'auto');
    $body.css('top', 0);
    $body.css('overflow-y', 'auto');
    window.scrollTo(0, scrollTop);
  }
  let unfold = true;

  $header
    .on('click', '.menu-item--expanded > span', function () {
      const $expMenu = $('header .menu-item--expanded');
      const $expMenuSpan = $('header .menu-item--expanded > span');
      $expMenu.find('.menu').stop().slideUp();
      $expMenuSpan.removeClass('iconfont icon icon-up');
      const $menu = $(this).siblings('.menu');
      if ($menu.css('display') !== 'block') {
        $(this).toggleClass('iconfont icon icon-up');
        $menu.removeAttr('style');
      }
      $menu.stop().slideToggle();
    })
    .on('click', '#block-pfrpsg-mobile-unfold-icon', () => {
      $('#block-pfrpsg-main-navigation').stop().slideToggle();
      $('#block-pfrpsg-assess-mycondition').toggle();
      $('#block-pfrpsg-speak-to-your-doctor').toggle();
      $('.header-menu-unfold').toggle();
      $('.header-menu-fold').toggle();
      unfold = !unfold;
      if (unfold) {
        recoverScroll();
      } else {
        stopScroll();
      }
    });
  $(window).on('resize', function () {
    if (window.innerWidth >= 1200) {
      recoverScroll();
    }
    if (
      window.innerWidth < 1200 &&
      $('#block-pfrpsg-main-navigation').css('display') === 'block' &&
      !$body.hasClass('overflowy-hidden')
    ) {
      stopScroll();
    }
  });
})(jQuery);
