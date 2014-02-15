var gulp = require('gulp');
var less = require('gulp-less');
var bower = require('gulp-bower');
var minifyCSS = require('gulp-minify-css');

var paths = {
  css: 'src/styles/**/*.less'
};

gulp.task('bower', function() {
  bower();
});

gulp.task('css', function() {
  return gulp.src(paths.css)
    .pipe(less())
    .pipe(minifyCSS())
    .pipe(gulp.dest('public/css'));
});

gulp.task('watch', function() {
  gulp.watch(paths.css, ['css']);
});

gulp.task('default', ['bower', 'css']);
