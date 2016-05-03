var gulp = require('gulp');

// 引入组件
var minifycss = require('gulp-minify-css'), // css 压缩
    uglify = require('gulp-uglify'), // js压缩
    concat = require('gulp-concat'), // 合并文件
    rename = require('gulp-rename'), // 重命名
    clean = require('gulp-clean'); // 清空文件夹

// 合并 压缩 重命名 css
gulp.task('stylesheets', function() {
    gulp.src('./blueairCode/common/css/*.css')
    .pipe(concat('all.css'))
    .pipe(gulp.dest('./blueairCode/common/css/'))
    .pipe(rename({suffix:'.min'}))
    .pipe(minifycss())
    .pipe(gulp.dest('./blueairCode/common/css'));
});