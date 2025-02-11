import gulp from 'gulp';
import sass from 'gulp-sass';
import concat from 'gulp-concat';
import cleanCSS from 'gulp-clean-css';
import obfuscator from 'gulp-javascript-obfuscator';
import del from 'del';
import webpack from 'webpack-stream';
import webpackConfig from './webpack.config';

const paths = {
  styles: {
    src: 'assets/styles/**/*.scss',
    dest: 'web/build/'
  },
  scripts: {
    src: 'assets/scripts/**/*.js',
    dest: 'web/build/'
  }
};

export const clean = (subDirectory = false) => del([`build/${(subDirectory ? '/' + subDirectory : '')}`]);

export function styles() {
  return gulp.src(paths.styles.src)
    .pipe(sass())
    .pipe(cleanCSS())
    .pipe(concat('styles.css'))
    .pipe(gulp.dest(paths.styles.dest));
}

export function scripts() {
  return gulp.src(paths.scripts.src, {sourcemaps: true})
    .pipe(
      webpack(webpackConfig)
        .on('error', (err) => {
          console.log(err.message);
        })
    )
    .pipe(obfuscator())
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest(paths.scripts.dest));
}

function watchFiles() {
  gulp.watch(paths.scripts.src, gulp.parallel(() => clean('scripts'), scripts));
  gulp.watch(paths.styles.src, gulp.parallel(() => clean('styles'), styles));
}

export {watchFiles as watch};

export const build = gulp.series(clean, gulp.parallel(styles, scripts));

export default watchFiles;