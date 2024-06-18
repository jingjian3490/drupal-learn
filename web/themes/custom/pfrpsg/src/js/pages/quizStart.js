import translates from '../constants/translates';
import { pfPageData, pfAnalyticsAttrData } from '../utils';

(($, Cookies) => {
  pfPageData('migraine risk assessment quiz');
  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'migraine risk assessment quiz | content | reference',
  );

  const $checkContainer = $('.quiz-start-check');
  const $agreeCheck = $('.quiz-start-check .form-checkbox');
  const $startBtn = $('.start-quiz .button');

  if (Cookies.get('agreeDisclaimer') === 'true') {
    $agreeCheck.trigger('click');
    $startBtn.addClass('agree-checked');
  }

  function showError() {
    $startBtn.removeClass('agree-checked');
    if (!$checkContainer.find('span.error').length) {
      $checkContainer.append(
        `<span class="error">${translates.agreeError}</span>`,
      );
    } else {
      $checkContainer.find('span.error').show();
    }
  }

  function pfAnalyticsAttrFormInitData() {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfFormWorkflow',
      pfFormWorkflow: {
        formName: 'migraine risk assessment quiz',
        formAction: 'initiated',
        formStep: '1',
        formPageLoad: 'false',
        formErrorMessage: '',
      },
    });

    const event = new CustomEvent('pfAnalytics', {
      detail: dataLayer,
    });
    document.querySelector('body').dispatchEvent(event);
  }

  $agreeCheck.on('change', function () {
    if ($(this).is(':checked')) {
      $startBtn.addClass('agree-checked');
      $checkContainer.find('span.error').hide();
      pfAnalyticsAttrData(
        'migraine risk assessment quiz | content | quiz | checkboxchecked',
      );
    } else {
      showError();
      pfAnalyticsAttrData(
        'migraine risk assessment quiz | content | quiz | checkboxunchecked',
      );
    }
  });

  $startBtn.on('click', function (e) {
    e.preventDefault();
    if (!$agreeCheck.is(':checked')) {
      showError();
      return false;
    }
    pfAnalyticsAttrFormInitData();
    Cookies.set('agreeDisclaimer', 'true');
    const random = Math.random().toFixed(6).slice(-6);
    setTimeout(() => {
      $(window.location).attr('href', `/quiz-page?${random}`);
    }, 0);
  });
})(jQuery, window.Cookies);
