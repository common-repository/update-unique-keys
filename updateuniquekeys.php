<?php
/*
Plugin Name: Update Unique Keys
Plugin URI: http://passavanti.name/wordpress-update-unique-keys
Description: This plugin will automatically set and/or update the Authenication Unique Keys in the wp-config.php file.
Version: 1.0.11
Author: Brian Passavanti
Author URI: https://profiles.wordpress.org/gottaloveit/
Text Domain: update-unique-keys

Copyright 2022  BRIAN_PASSAVANTI  (email : gottaloveit@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (defined('ABSPATH')) {
	if (!class_exists('updateuniquekeys')) {
		require_once(dirname(__FILE__) . '/updateuniquekeys.class.php');
	}

	$uukAbsPluginFolderName = dirname(__FILE__);
	$uukPluginFolderName = basename($uukAbsPluginFolderName);

	if (class_exists('updateuniquekeys')) {
		$updateuniquekeys_plugin = new updateuniquekeys('update-unique-keys',$uukAbsPluginFolderName.DIRECTORY_SEPARATOR.'locale',$uukPluginFolderName.DIRECTORY_SEPARATOR.'locale');
	}

	if (isset($updateuniquekeys_plugin)) {
		add_action('admin_menu', array($updateuniquekeys_plugin,'ActionAdminMenu'));
	}
} else {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	die();
}
?>
