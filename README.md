---

1. Copy, Edit and Run wp-setup.sh into the empty root of a New WP Project (es. htdocs/new-site)

2. Copy the content of Base Gulp Theme into ${sitename}-theme-dev

3. Replace all strings "sitename" and "stnm" with actual Website Name & Prefix in package.json and package-lock.json
   (for double/triple worded names, use dashes. eg. new-site)

4. Replace "Sitename" and "sitename-text-domain" with actual Theme Name & Text Domain in theme-dev src/sass/info/\_theme-info.scss

5. Replace "sitename-text-domain" with actual Text Domain in theme-dev functions.php

6. npm install --> Install all packages declared in package.json

7. npm start --> Start Dev Environment

8. npm run build --> Build Production Theme ready for deployment

9. Have fun! ;)
