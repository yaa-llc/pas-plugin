var gulp = require('gulp');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

gulp.task('dist', function() {
    gulp.src(['./public/js/images-loaded.js', './public/js/masonry.js', './public/js/jub-portfolio-gallery-public.js'])
        .pipe(concat('all.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./public/js/dist/'))
});