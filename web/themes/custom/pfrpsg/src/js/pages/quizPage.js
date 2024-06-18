import { pfPageData } from '../utils';

(($, Cookies) => {
  pfPageData('migraine risk assessment quiz question');

  const $defaultInput = $(
    '.paragraph--type--questions.paragraph--view-mode--default .question-input',
  );
  const $defaultQuestion = $(
    '.paragraph--type--questions.paragraph--view-mode--default .paragraph--type--question p',
  );
  const $previewInput = $(
    '.paragraph--type--questions.paragraph--view-mode--preview .question-input',
  );
  const $previewQuestion = $(
    '.paragraph--type--questions.paragraph--view-mode--preview .paragraph--type--question p',
  );
  const $submitBtn = $('.quiz-submit .button');

  function pfAnalyticsAttrFormInputData(
    formQuestion,
    formAnswer,
    formQuestionIndex,
  ) {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfFormWorkflowInput',
      pfFormWorkflowInput: {
        formName: 'migraine risk assessment quiz',
        formStep: '1',
        formQuestion,
        formAnswer,
        formQuestionIndex,
      },
    });

    const event = new CustomEvent('pfAnalytics', {
      detail: dataLayer,
    });
    document.querySelector('body').dispatchEvent(event);
  }

  function pfAnalyticsAttrFormStatusData(formAction = 'submitted', error = '') {
    const dataLayer = [];
    dataLayer.push({
      event: 'pfFormWorkflow',
      pfFormWorkflow: {
        formName: 'migraine risk assessment quiz',
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

  function setCookie(key, value) {
    const inOneHour = new Date(new Date().getTime() + 60 * 60 * 1000);
    Cookies.set(key, value, {
      expires: inOneHour,
    });
  }

  function initCookie() {
    if (!Cookies.get('defaultTestResults')) {
      setCookie('defaultTestResults', '[]');
    }
    if (!Cookies.get('previewTestResults')) {
      setCookie('previewTestResults', '[]');
    }
    if (!Cookies.get('agreeDisclaimer')) {
      setCookie('agreeDisclaimer', 'false');
    }
  }
  initCookie();

  function handleInput($inputList, $questionList, cookieName) {
    const testResults = [];
    for (let i = 0; i < $inputList.length; i++) {
      const $oneInput = $inputList.eq(i);
      const value = $oneInput.val();
      const questionText = $questionList.eq(i).text();
      const questionIndex = questionText.split('.')[0];
      const question = questionText.slice(
        parseInt(questionText.indexOf('.'), 10) + 2,
      );
      if (value !== '') {
        testResults.push(value);
        pfAnalyticsAttrFormInputData(question, value, questionIndex);
      } else {
        testResults.push('n');
      }
    }
    setCookie(cookieName, JSON.stringify(testResults));
  }

  // get result from cookie
  function getScore() {
    const testResult = JSON.parse(Cookies.get('defaultTestResults'));
    let score = 0;
    $.each(testResult, function (i, value) {
      if (value && value !== '' && value !== 'n') {
        const oneScore = parseInt(value, 10);
        if (oneScore !== 'NaN') {
          score += oneScore;
        }
      }
    });
    return score;
  }

  $submitBtn.attr(
    'sc:linkname',
    'migraine risk assessment quiz question | content | submit',
  );

  // scroll to
  function scrollToQuestion($ele) {
    $('html, body').animate(
      {
        scrollTop: $ele.offset().top - 100,
      },
      1000,
    );
  }

  function showErrorTips(emptyQuestion) {
    emptyQuestion.forEach(($ele) => {
      $ele.parent().next('.error').removeClass('hidden');
    });
  }

  $defaultInput.on('input', function () {
    $(this).parent().next('.error').addClass('hidden');
  });
  $previewInput.on('input', function () {
    $(this).parent().next('.error').addClass('hidden');
  });

  function submitPreCheck() {
    const emptyQuestion = [];
    [...$defaultInput].forEach((ele) => {
      if (!ele.value) {
        emptyQuestion.push($(ele));
      }
    });
    [...$previewInput].forEach((ele) => {
      if (!ele.value) {
        emptyQuestion.push($(ele));
      }
    });
    if (emptyQuestion.length > 0) {
      scrollToQuestion(emptyQuestion[0].parents('.input-box').prev());
      showErrorTips(emptyQuestion);
      return false;
    }
    return true;
  }

  $submitBtn.on('click', function (e) {
    e.preventDefault();
    const isAll = submitPreCheck();
    if (isAll) {
      $(this).css('pointer-events', 'none');
      $(this).css('background', 'gray');
      handleInput($defaultInput, $defaultQuestion, 'defaultTestResults');
      handleInput($previewInput, $previewQuestion, 'previewTestResults');
      const score = getScore();
      const random = Math.random().toFixed(6).slice(-6);
      $.ajax({
        url: `/quiz_statistic?${random}`,
        type: 'post',
        data: {
          score,
        },
        timeout: 5000,
        success: (res) => {
          const resData = res.data;
          pfAnalyticsAttrFormInputData('result', resData, 6);
          pfAnalyticsAttrFormStatusData();
          pfAnalyticsAttrFormStatusData('completed');
          setTimeout(() => {
            $(window.location).attr('href', `/quiz-result?${random}`);
            $submitBtn.css('pointer-events', 'auto');
            $submitBtn.css('background', 'var(--dark)');
          }, 4000);
        },
        error: () => {
          $submitBtn.css('pointer-events', 'auto');
          $submitBtn.css('background', 'var(--dark)');
        },
      });
    }
  });
})(jQuery, window.Cookies);
