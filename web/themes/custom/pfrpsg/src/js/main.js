// eslint-disable-next-line no-undef, camelcase
__webpack_public_path__ =
  '/profiles/migrainefreesg_profile/themes/pfrpsg/build/';

/*
 * Inital functions execer handler
 * @param modules
 */
const handleExecer = (modules) => {
  Object.keys(modules).forEach((item) => {
    const f = modules[item];
    if (typeof f === 'function') f();
  });
};

/**
 * './modules' import directory
 * true subdirectories
 * /\.js$/ match the suffix for 'js' file
 */
const files = require.context('./modules', true, /\.js$/);
const modules = files.keys().reduce((module, path) => {
  const name = path.replace(/^\.\/|.js$/g, '');
  module[name] = files(path).default;
  return module;
}, {});

handleExecer(modules);

jQuery(() => {
  const $ = jQuery;

  const $body = $('body');
  if ($body.hasClass('path-what-is-migraine')) {
    import(/* webpackChunkName: 'whatIsMigraine' */ './pages/whatIsMigraine');
  }
  if ($body.hasClass('path-migraine-or-headache')) {
    import(
      /* webpackChunkName: 'migraineOrHeadache' */ './pages/migraineOrHeadache'
    );
  }
  if ($body.hasClass('path-migraine-triggers')) {
    import(
      /* webpackChunkName: 'migraineTriggers' */ './pages/migraineTriggers'
    );
  }
  if ($body.hasClass('path-different-types-of-migraine')) {
    import(
      /* webpackChunkName: 'differentTypesOfMigraine' */ './pages/differentTypesOfMigraine'
    );
  }
  if ($body.hasClass('path-myths-vs-facts')) {
    import(/* webpackChunkName: 'mythsVsFacts' */ './pages/mythsVsFacts');
  }
  if ($body.hasClass('path-getting-diagnosed')) {
    import(
      /* webpackChunkName: 'gettingDiagnosed' */ './pages/gettingDiagnosed'
    );
  }
  if ($body.hasClass('path-types-of-treatments')) {
    import(
      /* webpackChunkName: 'typesOfTreatments' */ './pages/typesOfTreatments'
    );
  }
  if ($body.hasClass('path-lifestyle-tips')) {
    import(/* webpackChunkName: 'lifestyleTips' */ './pages/lifestyleTips');
  }
  if ($body.hasClass('path-migraine-risk-assessment-quiz')) {
    import(/* webpackChunkName: 'quizStart' */ './pages/quizStart');
  }
  if ($body.hasClass('path-quiz-page')) {
    import(/* webpackChunkName: 'quizPage' */ './pages/quizPage');
  }
  if ($body.hasClass('path-quiz-result')) {
    import(/* webpackChunkName: 'quizResult' */ './pages/quizResult');
  }
  if ($body.hasClass('path-migraine-toolkit')) {
    import(/* webpackChunkName: 'migraineToolkit' */ './pages/migraineToolkit');
  }
  if ($body.hasClass('path-find-a-doctor')) {
    import(/* webpackChunkName: 'findADoctor' */ './pages/findADoctor');
  }
  if ($body.hasClass('path-join-the-conversation')) {
    import(
      /* webpackChunkName: 'joinTheConversation' */ './pages/joinTheConversation'
    );
  }
  if ($body.hasClass('path-reference')) {
    import(/* webpackChunkName: 'reference' */ './pages/reference');
  }
});
