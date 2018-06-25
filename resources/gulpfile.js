/**
 * TESTE EPICS - TripAdvisor Parser
 *
 * @author Alexandre de Freitas Caetano <https://github.com/aledefreitas>
 */

var gulp = require('gulp');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var log = require('gulplog');
var rename = require('gulp-rename');
var rev = require('gulp-rev');
var revDel = require('rev-del');

var environment = 'dev';

gulp.task('js', function() {
    let build = browserify(__dirname + '/js/index.js')
        .bundle()
        .on('error', function(err) {
            console.log(err.message);
            this.emit('end');
        })
        .pipe(source('custom.js'))
        .pipe(buffer());

    if(environment === 'prod') {
        build.pipe(uglify())
    }

    build.pipe(gulp.dest(__dirname + '/../webroot/js/'))
    .pipe(rev())
    .pipe(gulp.dest(__dirname + "/../webroot/js/"))
    .pipe(rev.manifest(__dirname + "/../webroot/rev-manifest.json", {
        merge: true
    }))
    .pipe(revDel({
        dest: __dirname + "/../webroot/"
    }))
    .pipe(gulp.dest(__dirname + "/../webroot/"));

    return build;
});

gulp.task('css', function() {
    let build = gulp.src(__dirname + "/sass/main.scss")
    .pipe(sass({
        outputStyle: environment === 'prod' ? 'compressed' : 'expanded',
        loadPath: [
            __dirname + '/sass/',
        ]
    }).on('error', sass.logError))
    .pipe(rename('style.css'))
    .pipe(gulp.dest(__dirname + "/../webroot/css/"))
    .pipe(rev())
    .pipe(gulp.dest(__dirname + "/../webroot/css/"))
    .pipe(rev.manifest(__dirname + "/../webroot/rev-manifest.json", {
        merge: true
    }))
    .pipe(revDel({
        dest: __dirname + "/../webroot/"
    }))
    .pipe(gulp.dest(__dirname + "/../webroot/"));

    return build;
});

gulp.task('dev', function() {
    environment = 'dev';

    gulp.watch([
        __dirname + '/sass/*.scss',
        __dirname + '/js/**/*.js',
        __dirname + '/js/*.js'
    ], {
        ignoreInitial: false,
    }, [ 'js', 'css' ]);
});

gulp.task('prod', function() {
    environment = 'prod';

    gulp.start('js');
    gulp.start('css');
});
