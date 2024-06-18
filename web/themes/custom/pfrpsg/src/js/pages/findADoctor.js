import { pfPageData } from '../utils';

(function ($) {
  pfPageData('find a doctor');
  let zipCode;
  const $inputAddress = $('#edit-address');
  const inputCheckVal = [];

  function pfPharmacyFinder(a) {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfPharmacyFinder',
      pfPharmacyFinder: {
        zipCode: a,
        pharmacyPageLoad: 'false',
      },
    });

    const event = new CustomEvent('pfAnalytics', {
      detail: dataLayer,
    });
    document.querySelector('body').dispatchEvent(event);
  }

  function pfPharmacyFinderResults(pharmacyID, pharmacyName) {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfPharmacyFinderResults',
      pfPharmacyFinderResults: {
        pharmacyID,
        pharmacyName,
      },
    });

    const event = new CustomEvent('pfAnalytics', {
      detail: dataLayer,
    });
    document.querySelector('body').dispatchEvent(event);
  }

  function addWebFinderResults(type, websiteUrl, pharmacyName) {
    const currentDomain = window.location.hostname;
    if (
      websiteUrl.startsWith(`http://${currentDomain}`) ||
      websiteUrl.startsWith(`https://${currentDomain}`)
    ) {
      pfPharmacyFinderResults(`${type}:${websiteUrl}`, pharmacyName);
    } else {
      $('body').off('mousedown', '.ui-dialog-buttonset button:first-child');
      $('body').on(
        'mousedown',
        '.ui-dialog-buttonset button:first-child',
        function () {
          pfPharmacyFinderResults(`${type}:${websiteUrl}`, pharmacyName);
        },
      );
    }
  }

  function handleSearchBtn() {
    const checkedVal = [];
    $('#edit-zone--wrapper input').each((index, el) => {
      if ($(el).is(':checked')) {
        checkedVal.push($(el).val());
      }
    });
    if (checkedVal.length > 0) {
      if ($inputAddress.val().length > 0) {
        zipCode = `${$inputAddress.val()} | ${checkedVal.join(',')}`;
      } else {
        zipCode = checkedVal.toString();
      }
    } else {
      zipCode = $inputAddress.val();
    }
    if (zipCode !== '') {
      pfPharmacyFinder(zipCode);
    }
  }

  $('#edit-zone--wrapper input').on('change', function () {
    if ($(this).is(':checked')) {
      inputCheckVal.push($(this).val());
      handleSearchBtn();
    } else {
      const valIndex = inputCheckVal.indexOf($(this).val());
      inputCheckVal.splice(valIndex, 1);
      if (inputCheckVal.length > 0) {
        zipCode = inputCheckVal.toString();
        if ($inputAddress.val().length > 0) {
          zipCode = `${zipCode},${$inputAddress.val()}`;
        }
      } else if ($inputAddress.val().length > 0) {
        zipCode = $inputAddress.val();
      } else {
        zipCode = '';
      }
      if (zipCode !== '') {
        handleSearchBtn();
      }
    }
  });

  $('.clinic-filter-form .search-button').on('click', function () {
    handleSearchBtn();
  });

  $(document).on('keypress', function (event) {
    if (event.which === 13 || event.keyCode === 13) {
      handleSearchBtn();
    }
  });

  $('body').on('click', 'ul .clinic-tel-number a', function () {
    const pharmacyName = $(this).parents('li.row').find('.clinic-name').text();
    const phoneNumber = $(this).text();
    pfPharmacyFinderResults(`tel:${phoneNumber}`, pharmacyName);
  });

  $('body').on(
    'click',
    '.map-info-window-body .clinic-tel-number',
    function () {
      const pharmacyName = $(this)
        .parents('.map-info-window-box')
        .find('.map-info-window-title')
        .text();
      const phoneNumber = $(this).text();
      pfPharmacyFinderResults(`tel:${phoneNumber}`, pharmacyName);
    },
  );

  $('body').on('click', 'ul .clinic-website a', function () {
    const pharmacyName = $(this).parents('li.row').find('.clinic-name').text();
    const websiteUrl = $(this).text();
    addWebFinderResults('web', websiteUrl, pharmacyName);
  });

  $('body').on('click', '.map-info-window-body .clinic-website', function () {
    const pharmacyName = $(this)
      .parents('.map-info-window-box')
      .find('.map-info-window-title')
      .text();
    const websiteUrl = $(this).text();
    addWebFinderResults('web', websiteUrl, pharmacyName);
  });

  $('body').on('click', '.map-info-window-body .button-green-sm', function () {
    const pharmacyName = $(this)
      .parents('.map-info-window-box')
      .find('.map-info-window-title')
      .text();
    const websiteUrl = $(this).attr('href');
    addWebFinderResults('google maps', websiteUrl, pharmacyName);
  });

  let timer = null;

  function handleLinkName() {
    $('.pager a').each((index, el) => {
      const content = $(el).attr('title');
      $(el).attr('sc:linkname', `global pager | navigation | ${content}`);
    });
    $('.custom-field').each((index, el) => {
      const linkName = $(el).attr('linkname');
      if (linkName === undefined) {
        return true;
      }
      $(el).attr('sc:linkname', linkName);
      $(el).removeAttr('linkname');
    });
    $('.map-info-window-body .button-green-sm').each((index, el) => {
      const linkName = $(el).attr('linkname');
      if (linkName === undefined) {
        return true;
      }
      $(el).attr('sc:linkname', linkName);
      $(el).removeAttr('linkname');
    });
  }

  function handleTarget() {
    const target = document.getElementById('clinic-filter-form');
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

  $(document).ajaxComplete(function () {
    handleLinkName();
    handleTarget();
    clearInterval(timer);
    timer = setInterval(function () {
      handleLinkName();
    }, 1000);
  });
  $(document).ready(function () {
    handleLinkName();
  });
})(jQuery);
