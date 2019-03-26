import autoprefixer from 'autoprefixer';
import BrowserSync from 'browser-sync';
import cssnano from 'cssnano';
import del from 'del';
import gulp from 'gulp';
import gulpLoadPlugins from 'gulp-load-plugins';

const $ = gulpLoadPlugins();
const browserSync = BrowserSync.create();

import {watch, series, parallel, src, dest} from 'gulp';

const autoprefixer_plugin = [
  autoprefixer({
    browsers: [
      'last 2 versions',
      '> 1%',
      'ie >= 9',
      'ie_mob >= 10',
      'ff >= 30',
      'chrome >= 34',
      'safari >= 7',
      'opera >= 23',
      'ios >= 7',
      'android >= 4',
      'bb >= 10'
    ]
  })
];

const cssnano_plugin = [
  cssnano()
];

// Include paths file
import paths from './paths';

// Removes CSS files in public/dist folder
const cleanPublicCSS = () => del(paths.public_cssPublicDir);

// Removes CSS files in admin/dist folder
const cleanAdminCSS = () => del(paths.admin_cssPublicDir);

// Removes JS files in public/dist folder
const cleanPublicJS = () => del(paths.public_jsPublicDir);

// Removes JS files in public folder
const cleanAdminJS = () => del(paths.admin_jsPublicDir);

// Removes image files in public folder
const cleanPublicImages = () => del(paths.public_imagePublicDir);

// Removes image files in public folder
const cleanAdminImages = () => del(paths.admin_imagePublicDir);

// Compiles SCSS files into a single CSS file, adds prefixes,
// creates a sourcemap, and auto-inject into browsers
const publicStyles = () => src([
    `${paths.public_scssSourceDir}post-slideshow-plugin-public.scss`
  ])
  .pipe($.plumber())
  .pipe($.sass({
    style: 'compressed'
  }).on('error', $.sass.logError))
  .pipe($.concat('post-slideshow-plugin-public.css'))
  .pipe($.postcss(autoprefixer_plugin))
  .pipe($.postcss(cssnano_plugin))
  .pipe($.rename({
    basename: 'post-slideshow-plugin-public',
    suffix: '.min'
  }))
  .pipe(dest(paths.public_cssPublicDir))
  .pipe($.plumber.stop())
  .pipe($.sourcemaps.init())
  .pipe($.sourcemaps.write('.'))
  .pipe(dest(paths.public_cssPublicDir))
  .pipe($.gzip())
  .pipe($.size({
    gzip: true,
    showFiles: true
  }))
  .pipe(dest(paths.public_cssPublicDir))
  .pipe($.notify('Styles Task Completed'))
  .pipe(browserSync.stream());

// Compiles SCSS files into a single CSS file, adds prefixes,
// creates a sourcemap, and auto-inject into browsers
const adminStyles = () => src([
    `${paths.admin_scssSourceDir}post-slideshow-plugin-admin.scss`
  ])
  .pipe($.plumber())
  .pipe($.sass({
    style: 'compressed'
  }).on('error', $.sass.logError))
  .pipe($.concat('post-slideshow-plugin-admin.css'))
  .pipe($.postcss(autoprefixer_plugin))
  .pipe($.postcss(cssnano_plugin))
  .pipe($.rename({
    basename: 'post-slideshow-plugin-admin',
    suffix: '.min'
  }))
  .pipe($.plumber.stop())
  .pipe($.sourcemaps.init())
  .pipe($.sourcemaps.write('.'))
  .pipe(dest(paths.admin_cssPublicDir))
  .pipe($.gzip())
  .pipe($.size({
    gzip: true,
    showFiles: true
  }))
  .pipe(dest(paths.admin_cssPublicDir))
  .pipe($.notify('Styles Task Completed'))
  .pipe(browserSync.stream());

// Compiles JS files into a single JS file, creates a
// sourcemap and auto-inject into browsers
const publicScripts = () => src([
    `${paths.public_jsFilesGlob}`
  ])
  .pipe($.plumber())
  .pipe($.babel({
    presets: ['latest'],
    compact: true
  }))
  .pipe($.concat('post-slideshow-plugin-public.js'))
  .pipe($.uglify())
  .pipe($.rename({
    basename: 'post-slideshow-plugin-public',
    suffix: '.min'
  }))
  .pipe($.plumber.stop())
  .pipe($.sourcemaps.init())
  .pipe($.sourcemaps.write('.'))
  .pipe(dest(paths.public_jsPublicDir))
  .pipe($.gzip())
  .pipe($.size({
    gzip: true,
    showFiles: true
  }))
  .pipe(dest(paths.public_jsPublicDir))
  .pipe($.notify('Scripts Task Completed.'))
  .pipe(browserSync.stream());

// Compiles JS files into a single JS file, creates a
// sourcemap and auto-inject into browsers
const adminScripts = () => src([
    `${paths.admin_jsFilesGlob}`
  ])
  .pipe($.plumber())
  .pipe($.babel({
    presets: ['latest'],
    compact: true
  }))
  .pipe($.concat('post-slideshow-plugin-admin.js'))
  .pipe($.uglify())
  .pipe($.rename({
    basename: 'post-slideshow-plugin-admin',
    suffix: '.min'
  }))
  .pipe($.plumber.stop())
  .pipe($.sourcemaps.init())
  .pipe($.sourcemaps.write('.'))
  .pipe(dest(paths.admin_jsPublicDir))
  .pipe($.gzip())
  .pipe($.size({
    gzip: true,
    showFiles: true
  }))
  .pipe(dest(paths.admin_jsPublicDir))
  .pipe($.notify('Scripts Task Completed.'))
  .pipe(browserSync.stream());

// Optimizes images
const publicImages = () => src(paths.public_imageFilesGlob)
  .pipe($.imagemin([
    $.imagemin.gifsicle({
      interlaced: true
    }),
    $.imagemin.jpegtran({
      progressive: true
    }),
    $.imagemin.optipng(),
    $.imagemin.svgo({
      plugins: [{
        cleanupIDs: false
      }]
    })
  ], {
    verbose: true
  }))
  .pipe($.size({
    showFiles: true
  }))
  .pipe(dest(paths.public_imagePublicDir))
  .pipe($.notify('Images Task Completed.'))
  .pipe(browserSync.stream());

// Optimizes images
const adminImages = () => src(paths.admin_imageFilesGlob)
  .pipe($.imagemin([
    $.imagemin.gifsicle({
      interlaced: true
    }),
    $.imagemin.jpegtran({
      progressive: true
    }),
    $.imagemin.optipng(),
    $.imagemin.svgo({
      plugins: [{
        cleanupIDs: false
      }]
    })
  ], {
    verbose: true
  }))
  .pipe($.size({
    showFiles: true
  }))
  .pipe(dest(paths.admin_imagePublicDir))
  .pipe($.notify('Images Task Completed.'))
  .pipe(browserSync.stream());

// Build public assets
const buildPublicAssets = series(cleanPublicCSS, cleanPublicJS, cleanPublicImages, publicStyles, publicScripts, publicImages);

// Build admin assets
const buildAdminAssets = series(cleanAdminCSS, cleanAdminJS, cleanAdminImages, adminStyles, adminScripts, adminImages);

// Run BrowserSync server and sync any changes into it
const runServer = () => {
  browserSync.init({
    proxy: {
      target: 'https://banco.test'
    },
    open: false,
    injectChanges: true
  });

  // Watch files and execute corresponding Gulp task
  $.watch(paths.public_scssFilesGlob, series(publicStyles, browserSync.reload));
  $.watch(paths.admin_scssFilesGlob, series(adminStyles, browserSync.reload));
  $.watch(paths.public_jsFilesGlob, series(publicScripts, browserSync.reload));
  $.watch(paths.admin_jsFilesGlob, series(adminScripts, browserSync.reload));
  $.watch(paths.public_imageFilesGlob, series(cleanPublicImages, publicImages, browserSync.reload));
  $.watch(paths.admin_imageFilesGlob, series(cleanAdminImages, adminImages, browserSync.reload));
};

// Default Gulp tasks
const defaultTasks = parallel(buildPublicAssets, buildAdminAssets, runServer);

// Export tasks
export {cleanPublicCSS as cleanpubliccss};

export {cleanAdminCSS as cleanadmincss};
export {cleanPublicJS as cleanpublicjs};
export {cleanAdminJS as cleanadminjs};
export {cleanPublicImages as cleanpublicimages};
export {cleanAdminImages as cleanadminimages};
export {publicStyles as publicstyles};
export {adminStyles as adminstyles};
export {publicScripts as publicscripts};
export {adminScripts as adminscripts};
export {publicImages as publicimages};
export {adminImages as adminimages};
export {defaultTasks as default};
