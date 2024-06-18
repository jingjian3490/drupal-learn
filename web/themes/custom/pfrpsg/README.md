# Your theme name

## Develop with webpack

- install nodejs >= 16
- cd /path/to/theme
- npm install
- run `npm start` to develop locally
- run `npm run build` to genarate dist files
- (optional) run `npm run husky` bind git to husky (Change the directory pointing to.git)

## The directory structure

    dist (Automatically generated, example:)
    | example_page.bundle.js
    | projectName_theme.css
    | projectName_theme.js
    | ...
    node_modules
    | ...
    src
    | images
    | | ...
    | js
    | | modules (Automatic import)
    | | | example.js
    | | | ...
    | | pages (Manual import via main.js)
    | | | example.js
    | | utils (Public method)
    | | | exampleUtil.js
    | | | ...
    | | | index.js
    | | main.js
    | scss
    | | base
    | | | _breakpoints.scss
    | | | _reset.scss (*3)
    | | | _variables.scss
    | | layout
    | | | projectName.scss
    | | mixins
    | | | _media-queries.scss (*1)
    | | style.scss
    | | ...
    | index.js
    .babelrc.json
    .eslintcache
    .eslintrc.json
    .gitignore
    .prettierrc.json
    .stylelintrc.json
    projectName_theme.info.yml (*2)
    projectName_theme.libraries.yml
    favicon.ico
    logo.svg
    package-lock.json
    package.json
    postcss.config.js
    README.md
    webpack.common.js
    webpack.dev.js
    webpack.prod.js

## Reference
1. [Breakpoints and media queries in SCSS](https://medium.com/codeartisan/breakpoints-and-media-queries-in-scss-46e8f551e2f2)
2. [Adding Regions to a Theme](https://www.drupal.org/docs/theming-drupal/adding-regions-to-a-theme#default_regions)
3. [normalize.css](github.com/necolas/normalize.css)