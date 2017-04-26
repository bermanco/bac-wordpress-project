<?php

/**
 * Project Build Script
 */

// Load config file
if (!file_exists('project-config.json')){
	throw new Exception('You must create a project-config.json file before building the project.');
}
$config_file = file_get_contents('project-config.json');
$config = json_decode($config_file, true); // Decode to an array

// Run install/build commands
$install_commands = array(
	"wp core download",
	'composer install', // Install Composer dependencies (including required plugins)
	"cd web/app/themes/{$config['themeDirectoryName']} && npm install", // Install Node dependencies
	"cd web/app/themes/{$config['themeDirectoryName']} && bower install", // Install Bower dependencies
	"cd web/app/themes/{$config['themeDirectoryName']} && gulp build", // Build script for front end (JS and CSS)
	"wp rewrite flush --hard" // Create .htaccess for custom post types, etc.
);
foreach ($install_commands as $install_command){
	echo shell_exec($install_command);
}

// Activate Theme
echo shell_exec("wp theme activate " . $config['themeDirectoryName']);

// Install suggested plugins (these are not activated by default and the specific versions are not tracked in the repository)
foreach ($config['optionalPlugins'] as $plugin){
	echo shell_exec("wp plugin install $plugin");
}
