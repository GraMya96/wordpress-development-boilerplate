import { src, dest, watch, series, parallel } from 'gulp';
import yargs from 'yargs';
import sass from 'gulp-sass';
import cleanCss from 'gulp-clean-css';
import gulpif from 'gulp-if';
import postcss from 'gulp-postcss';
import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'autoprefixer';
import imagemin from 'gulp-imagemin';
import del from 'del';
import uglify from "gulp-uglify"
import named from 'vinyl-named';
import concat from 'gulp-concat';
import browserSync from "browser-sync";
import replace from "gulp-replace"
import webpack from 'webpack-stream';
import wpPot from "gulp-wp-pot";
import info from "./package.json";

const PRODUCTION = yargs.argv.prod;

/* TASK: Creating a server with Browser-sync & Enabling Auto-reloading */
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
// ------------------------------------------------



// TASK: Deleting dist folder
export const deleteDistFolder = () => {
    return del(['dist']);
}
// ------------------------------------------------



// TASK: Deleting development theme folder
export const deleteDevTheme = () => {
    return gulpif(PRODUCTION, del(
        [`../${info.name}_theme`],
        { force: true }
    ));
}
// ------------------------------------------------



/* TASK: Handling Styles:
 - handleSass: converting scss files and moving them into the src/css folder;
 - handleCss: moving all the files from the src/css folder to the dist/css folder
      and then Merging them into one file (style.css, needed for theme funcs) */
export const handleSass = () => {
    return src([
        'src/sass/**/*.scss'
    ])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init())) /* only in dev mode */
    .pipe(sass().on('error', sass.logError))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest("src/css/"))
    .pipe(server.stream());
}
export const handleCss = () => {
    return src([
        'src/css/**/*.css'
    ])
    .pipe(gulpif(!PRODUCTION, sourcemaps.init()))
    .pipe(gulpif(PRODUCTION, postcss([ autoprefixer ])))
    .pipe(gulpif(PRODUCTION, cleanCss({compatibility:'ie8'})))
    .pipe(gulpif(!PRODUCTION, sourcemaps.write()))
    .pipe(dest('dist/css'))
    .pipe(concat('style.css'))
    .pipe(dest('./'))
    .pipe(server.stream());
}
// ------------------------------------------------



/* TASK: Optimizing Images and moving them from src to dist folder */
export const images = () => {
    return src('src/img/**/*.{jpg,jpeg,png,svg,gif}')
    .pipe(gulpif(PRODUCTION, imagemin()))
    .pipe(dest('dist/img'));
}
// ------------------------------------------------



/* TASK: Copying all src content (not img/css/js folders) into dist folder */
export const copy = () => {
    return src(['src/**/*','!src/{img,js,css,sass}','!src/{img,js,css,sass}/**/*'])
    .pipe(dest('dist'));
}
// ------------------------------------------------


/* TASK: Bundling all JS files into one and compiling
    all the code into ES5 using respectively webpack and its loader
    babel-loader (@babel/preset-env package as 'presets of rules') */
export const webpackBundling = () => {
    return src([
        'src/js/**/*.js',
    ])
    .pipe(named())
    .pipe(webpack({
        module: {
            rules: [
                {
                    test: /\.js$/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
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
    .pipe(dest('dist/js'))
    .pipe(gulpif(PRODUCTION, uglify()))
    .pipe(concat('main.min.js'))
    .pipe(dest('./'));
}
// ------------------------------------------------


/* TASK: Preparing package for deployment (excluding unnecessary files) */
export const createProductionTheme = () => {
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
    "!wp-cli.txt",
    ])
    .pipe(dest(`../${info.name}_theme`));
};
// ------------------------------------------------


/* TASK: Creating a .pot file for translations */
export const pot = () => {
    return src("**/*.php")
    .pipe(
        wpPot({
            domain: `_${info.prefix}`,
            package: info.name
        })
    )
    .pipe(dest(`languages/${info.prefix}.pot`));
};
// ------------------------------------------------


/* TASK: Replacing "sitename_theme_dev" with "sitename_theme"
    and "Sitename Theme Dev" with "Sitename Theme" */
export const replaceDevString = () => {

    let capitalizedName;

    /* if double-word Name (like "Website Name")... */
    if((info.name).includes("-")) {
        const nameArray = (info.name).split("-");
        const newNameArray = [];

        nameArray.forEach((singleWord) => {
            newNameArray.push(singleWord.charAt(0).toUpperCase() + singleWord.slice(1));
        });

        capitalizedName = newNameArray.join(" ");
    }

    else {
        capitalizedName = (info.name).charAt(0).toUpperCase() + (info.name).slice(1);
    }

    return src("**/*.{php,scss,css}")
    .pipe(replace(`${info.name}_theme_dev`, `${info.name}_theme`))
    .pipe(replace(`${capitalizedName} Theme Dev`, `${capitalizedName} Theme`))
    .pipe(dest(`../${info.name}_theme`));
};
// ------------------------------------------------


/* TASK: Watch for Changes in any file */
export const watchForChanges = () => {
    watch('src/sass/**/*.scss', series(handleSass, handleCss));
    watch('src/img/**/*.{jpg,jpeg,png,svg,gif}', series(images, reload));
    watch(['src/**/*','!src/{img,js,css,sass}','!src/{img,js,css,sass}/**/*'], series(copy, reload));
    watch('src/js/**/*.js', series(webpackBundling, reload));
    watch("**/*.php", reload);
}
// ------------------------------------------------


export const dev = series(deleteDistFolder, parallel(handleSass, handleCss, images, copy), serve, watchForChanges);
export const build = series(deleteDistFolder, parallel(handleSass, handleCss, webpackBundling, images, copy), pot, deleteDevTheme, createProductionTheme, replaceDevString);
export default dev;