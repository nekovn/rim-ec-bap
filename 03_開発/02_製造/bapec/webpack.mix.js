const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
//**************** coreui ********************
mix.copy(
  'node_modules/@coreui/coreui/dist/js/coreui.bundle.min.js',
  'public/vendor/coreui/js'
);
mix.copy(
  'node_modules/@coreui/coreui/dist/js/coreui.bundle.min.js.map',
  'public/vendor/coreui/js'
);

//**************** images ********************
mix.copy('resources/images', 'public/images');

//**************** javascript, css define ********************
const glob = require('glob');
const sassBase = 'resources/sass';
const jsBase = 'resources/js';

const paths = {
  admin: {
    js: path.join(jsBase, 'admin/**'),
    sass: path.join(sassBase, 'admin/**'),
    destRoot: false
  },
  member: {
    js: path.join(jsBase, 'member/**'),
    sass: path.join(sassBase, 'member/**'),
    destRoot: true
  }
};

Object.keys(paths).forEach((key) => {
  //**************** css ********************
  glob
    .sync(path.join(paths[key].sass, '*.scss'), {
      ignore: [
        path.join(paths[key].sass, '_*.scss'),
        path.join(paths[key].sass, 'vendors/**')
      ]
    })
    .map((file) => {
      let destPath = 'public/css';
      if (paths[key].destRoot) {
        destPath = path.join(destPath, path.parse(file).base);
      } else {
        destPath = path.join(destPath, file.replace(sassBase, ''));
      }
      destPath = destPath.replace('.scss', '.css');

      mix.sass(file, destPath).version().sourceMaps();
    });
});
//**************** javascript ********************
glob
  .sync(path.join('resources/js/**', '*.js'), {
    ignore: [
      // `${paths.js.src}/app/*.js`,
      // `${paths.js.src}/base/**/*.js`,
      // `${paths.js.src}/service/*.js`,
      // `${paths.js.src}/app.js`,
      // `${paths.js.src}/app-config.js`,
      // `${paths.js.src}/const.js`
    ]
  })
  .map((file) => {
    let destPath = 'public/js';
    // if (file.indexOf(`${jsBase}/member`) >= 0) {
    //   destPath = path.join(destPath, file.replace(`${jsBase}/member`, ''));
    // } else {
      destPath = path.join(destPath, file.replace(jsBase, ''));
    // }
    mix.js(file, destPath).version().sourceMaps();
  });

mix.browserSync({
  files: ['./resources/**/*', './public/**/*'],
  proxy: {target: `"http://localhost:8000"`},
  open: false,
  reloadOnRestart: true
});

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.json'],
    alias: {
      '@js': `${__dirname}/resources/js`,
      '@sass': `${__dirname}/resources/sass`,
      '@sass-admin': `${__dirname}/resources/sass/admin`,
      '@sass-member': `${__dirname}/resources/sass/member`,
      '@sass-service': `${__dirname}/resources/sass/style/service`
    }
  },
  module: {
    rules: [
      {
        enforce: 'pre', // preを指定することで、付いてないローダーより先に実行できる。
        test: /\.(js)$/,
        loader: 'eslint-loader',
        exclude: /node_modules/,
        options: {
          fix: true // Lint実行時に自動整形を行うかどうか。（prettierのルールで自動整形してくれる）
        }
      }
    ]
  }
});
