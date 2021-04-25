#!/bin/bash
# ------------------------------------
# To run this bash script:
# 1) Search and replace "example" & "exmp" with the real new sitename & DB prefix;
# 2) Change all the default variables as needed, such as wp_admin_user, wp_admin_pass, PLUGINS_TO_INSTALL etc.
# 3) Type  bash wp-setup.sh  on the Command Line (root of the project dir);
# ------------------------------------

# Path:
# export PATH=$PATH:/mnt/c/wp-cli
# or
export PATH=$PATH:/mnt/c/xampp/htdocs/wp-cli-test

# Variables:
db_name="example_db"
db_username="root"
db_pass=""
db_prefix="exmp_"
hostname="127.0.0.1"
sitename="example"
website_url="https://127.0.0.1/ ${sitename}"
wp_admin_user="gramya96"
wp_admin_pass="merdamerda96"
wp_admin_email="mgramegnatota@gmail.com"
PLUGINS_TO_INSTALL[0]="yoast-seo"
PLUGINS_TO_INSTALL[1]="advanced-custom-field"
PLUGINS_TO_INSTALL[2]="all-in-one-wp-security-and-firewall"
acf_pro_local_path="..\..\..\Users\Utente\Desktop\Lavoro\WP Plugins and DBs\ACF Pro\advanced-custom-fields-pro.zip"
custom_ps_slug="news"
custom_ps_label="News"

# ---------------------------------------------------------

# Initialize Local Rep with GIT init:
# git init

# Download WP Core Files:
# wp core download

# Create config.php file filled with local phpmyadmin credentials, create .gitignore file
# and include wp-config.php, uploads folder and the Dev Environment theme in it:
wp config create --dbname=$db_name --dbuser=$db_username --dbpass=$db_pass --dbhost=$hostname --dbprefix=$db_prefix --allow-root
echo "wp-config.php<br />wp-content/uploads<br />wp-content/themes/${sitename}_theme_dev" > .gitignore

# Create local DB with wp-config.php data:
wp db create --dbuser=$db_username --dbpass=$db_pass --allow-root

# Create DB Wordpress Tables and Initialize WP:
wp core install --url=$website_url --title=$sitename --admin_user=$wp_admin_user --admin_password=$wp_admin_pass --admin_email=$wp_admin_email --allow-root

# Delete all themes:
wp theme delete --all

# Create new Custom Theme:
mkdir ".\wp-content\themes\ ${sitename}_theme_dev" #NOT WORKING

# Deactivate & Delete all default plugins and Install & Activate base needed Plugins:
wp plugin deactivate --all
wp plugin delete --all
wp plugin install ${PLUGINS_TO_INSTALL[@]} --activate
wp plugin install $acf_pro_local_path --activate

# Delete all default page and posts
wp post delete $(wp post list --post_type=post --format=ids --force)
wp post delete $(wp post list --post_type=page --format=ids --force)

# If needed, create a CPT
wp scaffold post-type $custom_ps_slug --label=$custom_ps_label --theme=${sitename}"_theme_dev"

# Confirmation Message
echo "${sitename} website installation completed. Have fun Coding ;)"
