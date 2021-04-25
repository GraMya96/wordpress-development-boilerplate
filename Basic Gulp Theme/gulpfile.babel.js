import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import postcss from 'gulp-postcss';
import concat from 'gulp-concat';
// import concatJs from 'gulp-concat-js';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import imagemin from 'gulp-imagemin';
import del from 'del';
import webpack from 'webpack-stream';
import named from 'vinyl-named';
import browserSync from "browser-sync";
// import zip from "gulp-zip";
import info from "./package.json";
import wpPot from "gulp-wp-pot";

const PRODUCTION = yargs.argv.prod;


// TASK: Creating a server with Browser-sync & Enabling Auto-reloading
const server = browserSync.create();
export const serve = done => {
    server.init({
        proxy: `https://localhost/${info.name}`
    });
    done();
};
export const reload = done => {
    server.reload();
    done();
};


// TASK: Deleting dist folder
export const clean = () => del(['dist']);
// ------------------------------------------------


// TASK: Handling Styles:
// - handleSass: converting scss files and moving them into the src/css folder;
// - handleCss: moving all the files from the src/css folder to the dist/css folder
//      and then Merging them into one file (style.css, needed for theme funcs)
export const handleSass = () => {
    return src([
        'src/sass/**/*.scss'
    ])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init())) // only in dev mode
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest("src/css/"))
    .pipe(server.stream());
}
export const handleCss = () => {
    return src([
        'src/css/**/*.css'
    ])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init())) // only in dev mode
    .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
    .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist/css'))
    .pipe(concat('style.css'))
    .pipe(dest('./'))
    .pipe(server.stream());
}
// ------------------------------------------------



// TASK: Optimizing Images and moving them from src to dist folder
export const images = () => {
    return src('src/img/**/*.{jpg,jpeg,png,svg,gif}')
    .pipe(gulpif(PRODUCTION, imagemin()))
    .pipe(dest('dist/img'));
}
// ------------------------------------------------



// TASK: Copying all src content (not img/css/js folders) into dist folder
export const copy = () => {
    return src(['src/**/*','!src/{img,js,css,sass}','!src/{img,js,css,sass}/**/*'])
    .pipe(dest('dist'));
}
// ------------------------------------------------


// TASK: Merging js files into one and moving
// it into dist folder
export const scripts = () => {
    return src(['src/js/main.js'])
    .pipe(named())
    .pipe(webpack({
        module: {
            rules: [
                {
                    test: /\.js$/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: []
                        }
                    }
                }
            ]
        },
        mode: PRODUCTION ? 'production' : 'development',
        devtool: !PRODUCTION ? 'inline-source-map' : false,
        output: {
            filename: '[name].js'
        }
    }))
    .pipe(dest('dist/js'));
}
// ------------------------------------------------


// TASK: Preparing package for deployment (excluding unnecessary files)
export const compress = () => {
    return src([
    "**/*",
    "!node_modules{,/**}",
    "!bundled{,/**}",
    "!src{,/**}",
    "!.babelrc",
    "!.gitignore",
    "!gulpfile.babel.js",
    "!package.json",
    "!package-lock.json",
    ])
    // .pipe(
    //     gulpif(
    //         file => file.relative.split(".").pop() !== "zip",
    //         replace("_themename", info.name)
    //     )
    // )
    .pipe(dest(`../${info.name}_theme`));
};
// ------------------------------------------------


// TASK: Creating a .pot file for translations
export const pot = () => {
    return src("**/*.php")
    .pipe(
        wpPot({
            domain: "_lprd",
            package: info.name
        })
    )
    .pipe(dest(`languages/${info.name}.pot`));
};
// ------------------------------------------------


// TASK: Watch for Changes in any file
export const watchForChanges = () => {
    watch('src/sass/**/*.scss', handleSass, handleCss);
    watch('src/img/**/*.{jpg,jpeg,png,svg,gif}', series(images, reload));
    watch(['src/**/*','!src/{img,js,css}','!src/{img,js,css}/**/*'], series(copy, reload));
    watch('src/js/**/*.js', series(scripts, reload));
    watch("**/*.php", reload);
}
// ------------------------------------------------


export const dev = series(clean, parallel(handleSass, handleCss, images, copy, scripts), serve, watchForChanges);
export const build = series(clean, parallel(handleSass, handleCss, images, copy, scripts), pot, compress);
export default dev;