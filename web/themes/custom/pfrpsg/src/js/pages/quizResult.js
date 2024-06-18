import { pfPageData, pfAnalyticsAttrData } from '../utils';

(($) => {
  pfPageData('test results');

  const $scrollContainer = $('.scroll-container');
  const $tableContainer = $('.table-container');
  const $emailBtn = $('.button-email');
  const $pdfBtn = $('.button-pdf');
  const $searchBtn = $('.button-search');
  const $closeIcon = $('.popup-close');
  const $sendEmailPopup = $('.popup');
  const $submit = $('.stereo-button');
  const $emailInput = $('.head-container input');
  const $inputError = $('.head-container span.error');

  const $bottomReferenceLink = $('.field--name-field-reference-and-gcma a');
  $bottomReferenceLink.attr(
    'sc:linkname',
    'test results | content | reference',
  );

  function pfAnalyticsAttrFormData(
    formName,
    formAction = 'initiated',
    error = '',
  ) {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfFormWorkflow',
      pfFormWorkflow: {
        formName,
        formAction,
        formStep: '1',
        formPageLoad: 'false',
        formErrorMessage: error,
      },
    });

    const event = new CustomEvent('pfAnalytics', {
      detail: dataLayer,
    });
    document.querySelector('body').dispatchEvent(event);
  }

  function pfAnalyticsAttrFormInputData() {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfFormWorkflowInput',
      pfFormWorkflowInput: {
        formName: 'test results email',
        formStep: '1',
        formQuestion: 'email address for test results',
        formAnswer: 'NA',
        formQuestionIndex: '1',
      },
    });

    const event = new CustomEvent('pfAnalytics', {
      detail: dataLayer,
    });
    document.querySelector('body').dispatchEvent(event);
  }

  function mutaCallback() {
    const $content = $('.messages');
    if ($content.length > 0) {
      if ($content.attr('aria-label') === 'Error message') {
        pfAnalyticsAttrFormData(
          'test results email',
          'error',
          'Unable to send email. Contact the site administrator if the problem persists.',
        );
      } else {
        pfAnalyticsAttrFormData('test results email', 'completed');
      }
    }
  }

  let timer = null;
  timer = setInterval(function () {
    if (window.pfAnalyticsData._baseMetadata) {
      mutaCallback();
      clearInterval(timer);
    }
  }, 1000);

  // check email
  function emailCheck(email = '') {
    if (!email) {
      return false;
    }
    if (email.length > 254) {
      return false;
    }
    /* eslint-disable */
    const strRegex =
      /^[-!#$%&'*+\/0-9=?A-Z^_a-z{|}~](\.?[-!#$%&'*+\/0-9=?A-Z^_a-z`{|}~])*@[a-zA-Z0-9](-*\.?[a-zA-Z0-9])*\.[a-zA-Z](-?[a-zA-Z0-9])+$/;
    if (!strRegex.test(email)) {
      return false;
    }

    const parts = email.split('@');
    if (parts[0].length > 64) {
      return false;
    }

    const domainParts = parts[1].split('.');
    if (
      domainParts.some(function (part) {
        return part.length > 63;
      })
    ) {
      return false;
    }
    return true;
  }
  // disabled submit or not
  function disabledSubmit(disabled = false) {
    if (disabled) {
      $submit.attr('disabled', 'disabled');
    } else {
      $submit.removeAttr('disabled');
    }
  }
  // Processing after mail format check
  function postCheckProcessing(isCorrect) {
    if (isCorrect === undefined || isCorrect === null) {
      return;
    }
    if (isCorrect === false) {
      $emailInput.addClass('error');
      $inputError.removeClass('hidden');
    } else {
      $emailInput.removeClass('error');
      $inputError.addClass('hidden');
    }
  }

  $tableContainer.scroll(function () {
    const n1 = parseInt(this.scrollLeft, 10);
    const n2 = parseInt(this.clientWidth, 10);
    const n3 = parseInt(this.scrollWidth, 10);
    if (n1 + n2 === n3) {
      $scrollContainer.addClass('mask-hidden');
    } else {
      $scrollContainer.removeClass('mask-hidden');
    }
  });

  $pdfBtn.attr('sc:linkname', 'test results | navigation | save as pdf');
  const random = Math.random().toFixed(6).slice(-6);
  $pdfBtn.attr('href', `/pdf-generate?${random}`);
  $searchBtn.attr('sc:linkname', 'test results | navigation | find a medical specialist');

  $emailBtn.on('click', () => {
    $sendEmailPopup.removeClass('hidden');
    postCheckProcessing(true);
    disabledSubmit(false);
    $emailInput.prop('value', '');
    pfAnalyticsAttrData('test results | navigation | email');
    pfAnalyticsAttrFormData('test results email');
  });

  $closeIcon.on('click', function () {
    $sendEmailPopup.addClass('hidden');
  });
  $emailInput.focus(function () {
    postCheckProcessing(true);
  });
  $submit.on('click', function (e) {
    e.preventDefault();
    pfAnalyticsAttrData(
      'test results | content | test results email | email',
    );
    pfAnalyticsAttrFormInputData();
    const value = $emailInput.prop('value');
    const isCorrect = emailCheck(value);
    postCheckProcessing(isCorrect);
    if (isCorrect) {
      this.form.submit();
      disabledSubmit(true);
      pfAnalyticsAttrFormData('test results email', 'submitted');
    } else {
      pfAnalyticsAttrFormData(
        'test results email',
        'error',
        'Email address is null/has wrong format',
      );
      return false;
    }
  });
})(jQuery);
