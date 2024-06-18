(($) => {
  const $expMenu = $('header .menu-item--expanded');
  const $expMenuSpan = $('header .menu-item--expanded > span');

  function notTarget(target, $ele) {
    return !$ele.is(target) && $ele.has(target).length === 0;
  }

  $(document).on('click', function (e) {
    const { target } = e;
    if (notTarget(target, $expMenu)) {
      $expMenu.find('.menu').stop().slideUp();
      $expMenuSpan.removeClass('iconfont icon icon-up');
    }
  });
})(jQuery);
