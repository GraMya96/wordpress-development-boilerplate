# ------------------------------------
# To run this bash script:
# 1) Search and replace "example" & "exmp" with the real new sitename & DB prefix;
# 2) Change all the default variables as needed, such as wp_admin_user, wp_admin_pass etc.
# 3*) Default port for DB installation is 3307, change it into 3306 if needed;
# 4) Type  bash wp-setup.sh  on the Command Line (root of the project dir);
# ------------------------------------

# Variables:
db_name="example_db"
db_username="root"
db_pass=""
db_prefix="exmp_"
default_port="3307"
hostname="127.0.0.1:${default_port}"
sitename="example"
website_url="https://localhost/${sitename}"
wp_admin_user="gramya96"
wp_admin_pass="password_esempio"
wp_admin_email="mgramegnatota@gmail.com"
PLUGINS_TO_INSTALL[0]="seo-by-rank-math"
PLUGINS_TO_INSTALL[1]="advanced-custom-fields"
PLUGINS_TO_INSTALL[2]="all-in-one-wp-security-and-firewall"
custom_ps_slug="news"
custom_ps_label="News"

# ---------------------------------------------------------

# Initialize Local Rep with GIT init:
# git init

# Download WP Core Files:
# wp core download

# Create config.php file filled with local phpmyadmin credentials and delete wp-config-sample.php
sudo wp config create --dbname=$db_name --dbuser=$db_username --dbpass=$db_pass --dbhost=$hostname --dbprefix=$db_prefix --allow-root
sudo rm wp-config-sample.php

# Create .gitignore file and include wp-config.php,
# uploads folder and the Dev Environment theme in it:
echo "wp-config.php
wp-content/themes/${sitename}_theme_dev" > .gitignore

# Create local DB based on wp-config.php data:
sudo wp db create --allow-root

# Create DB Wordpress Tables and Initialize WP:
sudo wp core install --url=$website_url --title=$sitename --admin_user=$wp_admin_user --admin_password=$wp_admin_pass --admin_email=$wp_admin_email --allow-root

# Delete all themes:
wp theme delete --all

# Create new Custom Theme:
mkdir "wp-content/themes/${sitename}_theme_dev"

# Deactivate & Delete all default plugins and Install & Activate base needed Plugins:
wp plugin deactivate --all
wp plugin delete --all
wp plugin install ${PLUGINS_TO_INSTALL[@]} --activate

# Delete all default page and posts
wp post delete $(wp post list --post_type=post --format=ids --force)
wp post delete $(wp post list --post_type=page --format=ids --force)

# If needed, create a CPT
wp scaffold post-type $custom_ps_slug --label=$custom_ps_label --theme=${sitename}"_theme_dev"

# Confirmation Message
echo "${sitename} website installation completed. Have fun Coding ;)"
