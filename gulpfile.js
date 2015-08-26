var gulp = require('gulp');
var sass = require('gulp-sass');
var bower = require('gulp-bower');
var minifyCSS = require('gulp-minify-css');

var paths = {
  css: 'src/styles/**/*.scss',
  fonts: 'bower_components/font-awesome/fonts/**/*'
};

gulp.task('bower', function() {
  return bower();
});

gulp.task('css', ['bower'], function() {
  return gulp.src(paths.css)
    .pipe(sass())
    .pipe(minifyCSS())
    .pipe(gulp.dest('public/css'));
});

gulp.task('fonts', ['bower'], function() {
  return gulp.src(paths.fonts)
    .pipe(gulp.dest('public/fonts'));
});

gulp.task('watch', function() {
  gulp.watch(paths.css, ['css']);
});

gulp.task('default', ['css', 'fonts']);
