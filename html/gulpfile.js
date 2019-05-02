const gulp = require('gulp');
const webpack = require('webpack');
const webpack_stream = require('webpack-stream');
const webpack_config = require('./webpack.config.js');
const sass = require('gulp-sass');
sass.compiler = require('node-sass');
const cssmin = require('gulp-cssmin');
const autoprefixer = require('gulp-autoprefixer');
const htmlmin = require('gulp-htmlmin');
const imagemin = require('gulp-imagemin');
const browserSync = require('browser-sync').create();
const browserSyncReload = browserSync.reload;
const rename = require('gulp-rename');
const htmlReplace = require('gulp-html-replace');
const connect_php = require('gulp-connect-php');
const fs = require('fs');

const paths = {
    "src_assets": "./src/assets/",
    "src": "./src/",
    "build": "./dist/"
};

function es6plusDevCompile(){
    return gulp.src(`${paths.src_assets}scripts/js/main.js`)
    .pipe(webpack_stream({
        mode: 'development',
        output: {
            filename: 'bundle.min.js'
        },
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /node_modules/,
                    use: {
                        loader: 'babel-loader',
                    }
                }
            ],
        }
    }, webpack))
    .pipe(gulp.dest(`${paths.src_assets}scripts`));
}

function es6plusCompile(){
    return webpack_stream(webpack_config)
    .pipe(gulp.dest(`${paths.src_assets}scripts`));
}

function sassCompile(){
    return gulp
    .src(`${paths.src_assets}scss/style.scss`)
    .pipe(sass())
    .pipe(autoprefixer({
        browsers: ['last 2 versions'],
        cascade: false
    }))
    .pipe(cssmin())
    .pipe(gulp.dest(`${paths.src_assets}css`));
}

function watcher(){
    gulp.watch(`${paths.src_assets}scripts/js/**/*.js`, es6plusDevCompile);
    gulp.watch(`${paths.src_assets}scss/**/*.scss`, sassCompile).on("change", browserSyncReload);
    gulp.watch([`${paths.src}*.html`, `${paths.src}*.php`]).on("change", browserSyncReload);
    gulp.watch(`${paths.src_assets}scripts/*.js`).on("change", browserSyncReload);
}

function createReadmeToDist(){
    let projectInfo = require(`${paths.src}project-info.json`);
    let readmeStructure = `#${projectInfo.project_name}\n${projectInfo.project_description}\n\n__Last change__ > ${new Date()}\n\n__Compiled by__ > ${require('./package.json').name}\n\n__Created by__ > ${projectInfo.project_author}\n\n*Version __${projectInfo.project_version}__*`;

    fs.writeFileSync(`${paths.build}readme.md`, readmeStructure);
}

gulp.task('minify-html', () =>{
    return gulp.src([`${paths.src}*.html`, `${paths.src}*.php`])
    .pipe(htmlReplace({
        js: 'assets/js/bundle.min.js'
    }))
    .pipe(htmlmin({ 
        collapseWhitespace: true,
        ignoreCustomFragments: [ /<%[\s\S]*?%>/, /<\?[=|php]?[\s\S]*?\?>/ ]
    }))
    .pipe(gulp.dest(`${paths.build}`));
});

gulp.task('move-scripts', () =>{
    return gulp.src(`${paths.src_assets}scripts/*.js`)
    .pipe(rename(path => {
        path.dirname = "js";
    }))
    .pipe(gulp.dest(`${paths.build}assets`));
});

gulp.task('move-others-folders', () =>{
    let filesToMove = {
        css: `${paths.src_assets}css/*`,
        fonts: `${paths.src_assets}fonts/*`,
        images: `${paths.src_assets}images/*`
    };

    gulp.src(filesToMove.css).pipe(gulp.dest(`${paths.build}assets/css`));
    gulp.src(filesToMove.fonts).pipe(gulp.dest(`${paths.build}assets/fonts`));
    return gulp.src(filesToMove.images).pipe(imagemin()).pipe(gulp.dest(`${paths.build}assets/images`));
});

gulp.task('default', () => {
    connect_php.server({
        base: './src'
    }, function(){
        browserSync.init({
            proxy: '127.0.0.1:8000'
        })
    })
    watcher();
});

gulp.task('init-compile', () => {
    createReadmeToDist();
    es6plusCompile();
    return sassCompile();
});

gulp.task('dist', gulp.series('minify-html', 'move-scripts', 'move-others-folders'));