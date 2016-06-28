/**
 * Created by PhpStorm.
 * User: Guillaume Loulier | guillaume.loulier[at]hotmail.fr
 */

var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var concat = require('gulp-concat');
var minify_css = require('gulp-minify-css');

var config = {
    assets_dir : 'web/assets/sass/',
    sass_dir :  'sass/Style/Core/*.scss'
};

gulp.task('sass', function () {
    gulp.src(config.assets_dir + '/' + config.sass_dir)
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(concat('main.css'))
        .pipe(minify_css())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('web/css/Main'))
});

gulp.task('watch', function () {
    gulp.watch(config.assets_dir + '/' + config.sass_dir, ['sass'])
});

gulp.task('default', ['sass', 'watch']);