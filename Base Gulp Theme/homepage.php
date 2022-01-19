<?php
/**
 * Template Name: Homepage
*/

get_header();
?>

<h1>Homepage</h1>


<?php get_footer();

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







