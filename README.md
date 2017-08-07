# BAC WordPress Project Framework

This is a modified version of [Bedrock](https://roots.io/bedrock/).  It maintains the same directory structure, but no longer requires that WordPress and all plugins be installed and maintained through Composer.  While keeping everything in Composer is great for projects that a developer has total control over and is able to maintain indefintiely, it is not always possible for client work.

Basically, if you are willing to funnel all WordPress and plugin updates through Composer, **do not use this**.  Real Bedrock is a great choice.

The main changes from Bedrock are:

* **Automatic WordPress updates and file modifications (ie, plugin updates/installs) are no longer disabled in the admin interface.** Since WordPress is obsessive about maintaining backwards compatibility, allowing the client to perform those updates is usually pretty harmless.  Plugin updates are more likely to cause issues, but the alternative is often leaving them completely un-updated for years at a time.  Random plugins installed by the client are a security and stability risk, but they also expect that to be an option.
* **Plugins that ARE managed by Composer are assumed to be required for the site to function, and are installed in the mu-plugins directory**
* **"Optional" plugins are defined in a configuation file, and installed (but not activated) using wp-cli when the build script is run**

## Features Inherited from Bedrock

* Better folder structure
* Dependency management with [Composer](http://getcomposer.org)
* Easy WordPress configuration with environment specific files
* Environment variables with [Dotenv](https://github.com/vlucas/phpdotenv)
* Autoloader for mu-plugins (use regular plugins as mu-plugins)

## Requirements

* PHP >= 5.6
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* wp-cli - This assumes that the `wp` command is mapped to wp-cli

## Installation

1. Create a new project in a new folder for your project:

  `git clone https://github.com/bermanco/bac-wordpress-project.git`

2. Copy `.env.example` to `.env` and update environment variables:
  * `DB_NAME` - Database name
  * `DB_USER` - Database user
  * `DB_PASSWORD` - Database password
  * `DB_HOST` - Database host
  * `WP_ENV` - Set to environment (`development`, `staging`, `production`)
  * `WP_HOME` - Full URL to WordPress home (http://example.com)
  * `WP_SITEURL` - Full URL to WordPress including subdirectory (http://example.com/wp)
  * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`

  If you want to automatically generate the security keys (assuming you have wp-cli installed locally) you can use the very handy [wp-cli-dotenv-command][wp-cli-dotenv]:

      wp package install aaemnnosttv/wp-cli-dotenv-command

      wp dotenv salts regenerate

  Or, you can cut and paste from the [Roots WordPress Salt Generator][roots-wp-salt].

3. Add theme(s) in `web/app/themes` as you would for a normal WordPress site.

4. Copy `project-config.json.example` to `project-config.json` and provide values for:
  * `themeDirectoryName` - the directory name of the site's theme (**not** the full name defined in style.css)
  * `optionalPlugins` - Provide the slugs of plugins that will be installed during the build process for the site.  They will not be automatically activated.

5. Run `wp core download` to download the WordPress core files.

6. Install WordPress with the following command, replacing the placeholders with real values: `wp core install --url="DEV_URL" --title="SITE TITLE" --admin_user="ADMIN_USERNAME" --admin_password="ADMIN_PASSWORD" --admin_email="ADMIN_EMAIL"`

5. Run the build script, `php build-project.php`, to install WordPress, installed Composer dependencies, install optional plugins, and run the front end build processes (NPM, Bower, Gulp, etc.)

6. Set your site vhost document root to `/path/to/site/web/`

7. Access WP admin at `http://example.com/wp/wp-admin`

## Deploys

Any deployment method can be used with one requirement:

`php build-project.php` must be run as part of the deploy process.

## Bedrock Documentation

Bedrock's documentation is still mostly relevant, and is available at [https://roots.io/bedrock/docs/](https://roots.io/bedrock/docs/).

