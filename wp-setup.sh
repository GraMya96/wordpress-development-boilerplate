# ------------------------------------
# To run this bash script:
# 1) Search and replace "example" & "exmp" with the real new sitename & DB prefix;
# 2) Change all the default variables as needed, such as wp_admin_user, wp_admin_pass etc.
# 3*) Local PHP and MySQL must be on. Default port for DB installation is 3307, change it into 3306 if needed;
# 4) Type bash wp-setup.sh  on the Command Line (root of the project directory);
# ------------------------------------

# Variables:
db_name="${sitename}_db"
db_username="root"
db_pass=""
db_prefix="exmp_"
default_port="3307"
hostname="127.0.0.1:${default_port}"
sitename="example"
website_url="https://localhost/${sitename}"
wp_admin_user="username_example"
wp_admin_pass="password_example"
wp_admin_email="example@mail.com"
PLUGINS_TO_INSTALL[0]="seo-by-rank-math"
PLUGINS_TO_INSTALL[1]="advanced-custom-fields"
PLUGINS_TO_INSTALL[2]="all-in-one-wp-security-and-firewall"
PLUGINS_TO_INSTALL[3]="wp-migrate-db"
PLUGINS_TO_INSTALL[4]="query-monitor"
PLUGINS_TO_INSTALL[5]="wp-asset-clean-up"
today_date=$(date +%Y-%m-%d)
custom_ps_slug="news"
custom_ps_label="News"
# custom_ps_slug_2="test"
# custom_ps_label_2="Test"

# ---------------------------------------------------------

# Initialize Local Rep with GIT init:
git init

# Download WP Core Files:
wp core download

# Create config.php file filled with local phpmyadmin credentials and delete wp-config-sample.php:
sudo wp config create --dbname=$db_name --dbuser=$db_username --dbpass=$db_pass --dbhost=$hostname --dbprefix=$db_prefix --allow-root
sudo rm wp-config-sample.php

# Create .gitignore file and include wp-config.php,
# uploads folder and the Dev Environment theme in it:
echo "wp-config.php
wp-content/themes/${sitename}-theme-dev" > .gitignore

# Create local DB based on wp-config.php data:
sudo wp db create --allow-root

# Create DB Wordpress Tables and Initialize WP:
sudo wp core install --url=$website_url --title=${sitename^} --admin_user=$wp_admin_user --admin_password=$wp_admin_pass --admin_email=$wp_admin_email --allow-root

# Delete all themes:
wp theme delete --all

# Create new Custom Theme:
mkdir "wp-content/themes/${sitename}-theme-dev"

# Deactivate & Delete all default plugins and Install & Activate base needed Plugins:
wp plugin deactivate --all
wp plugin delete --all
wp plugin install ${PLUGINS_TO_INSTALL[@]} --activate

# Delete all default page and posts:
wp post delete $(wp post list --post_type=post --format=ids --force)
wp post delete $(wp post list --post_type=page --format=ids --force)

# Create Homepage, 404 and Privacy Policy pages:
wp post create --post_title='Homepage' --post_type=page --post_status=publish
wp post create --post_title='404' --post_type=page --post_status=publish
wp post create --post_title='Privacy Policy' --post_type=page --post_status=publish

# If needed, create a CPT:
wp scaffold post-type $custom_ps_slug --label=$custom_ps_label --theme=${sitename}"-theme-dev"
# wp scaffold post-type $custom_ps_slug_2 --label=$custom_ps_label_2 --theme=${sitename}"-theme-dev"

# If needed, create Categories:
# wp term create category "Media Partner"

# Confirmation Message:
echo "${sitename} website installation completed. Have fun Coding ;)"
