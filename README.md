------------------------------

1) Copy, Edit and Run wp-setup.sh into the empty root of a New WP Project (es. htdocs/new-site)

2) If you want to start with _s (Underscore Theme - https://underscores.me/):
    - Copy the content of Basic Gulp Theme/underscore-starter into ${sitename}_theme_dev

    If you DO NOT:
    - Copy the content of Basic Gulp Theme/base-starter into ${sitename}_theme_dev

3) Replace all strings "sitename" and "stnm" with actual Website Name & Prefix in package.json and package-lock.json
    (for double/triple worded names, use dashes. eg. new-site)

4) Replace "example" and "exmp" with actual Theme Name & Text Domain in theme_dev sass/base.scss

5) npm install --> Install all packages declared in package.json

6) npm start --> Start Dev Environment

7) npm run build --> Build Production Theme ready for deployment

8) Have fun! ;)

---------------

If you want to use Tailpress (Tailwind CSS Theme Boilerplate by Jeffrey van Rossum - https://github.com/jeffreyvr/tailpress),
	inside the tailpress theme directory:
	
	1) Run npm install
	
	2) Run npm run watch to start developing

------------------------------
