var gulp = require('gulp'),
  babel = require('gulp-babel'),
  browserify = require('browserify'),
  source = require('vinyl-source-stream'),
  buffer = require('vinyl-buffer'),
  rename = require('gulp-rename'),
  uglify = require('gulp-uglify'),
  del = require('del'),
  requirejs = require('gulp-requirejs'),
  cleanCSS = require('gulp-clean-css');

gulp.task('minify-css', () => {
  return gulp.src(['src/*.css','src/**/*.css'])
    .pipe(cleanCSS({compatibility: 'ie8'}))
    .pipe(gulp.dest('./PRODUCTION/src'));
});

gulp.task('clean-temp', function(){
  return del(['lib']);
});

gulp.task('es6-commonjs',['clean-temp'], function(){
  return gulp.src(['src/*.js','src/**/*.js'])
    .pipe(babel({presets: ['es2015', 'react', 'stage-2']}))
    .pipe(uglify())
    .pipe(gulp.dest('./PRODUCTION/src'));
});

gulp.task('bundle-commonjs-clean', function(){
  return del(['dist']);
});

gulp.task('commonjs-bundle',['bundle-commonjs-clean','es6-commonjs', 'minify-css']);

gulp.task('default', ['commonjs-bundle']);

