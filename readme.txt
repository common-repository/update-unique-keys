=== Update Unique Keys ===
Contributors: gottaloveit
Donate link: http://passavanti.name/donate
Tags: secret keys, Authentication Unique Keys, wp-config, AUTH_KEY, SECURE_AUTH_KEY, LOGGED_IN_KEY, NONCE_KEY, AUTH_SALT, SECURE_AUTH_SALT, LOGGED_IN_SALT, NONCE_SALT
Requires at least: 2.6
Tested up to: 6.0
Stable tag: 1.0.11

This plugin will automatically set and/or update the Authenication Unique Keys in the wp-config.php file.

== Description ==

In an effort to help make Wordpress installations more secure, this plugin will use the Wordpress hosted Unique Key generator to update the wp-config.php file with the following keys/salts:

*  AUTH_KEY
*  SECURE_AUTH_KEY
*  LOGGED_IN_KEY
*  NONCE_KEY
*  AUTH_SALT
*  SECURE_AUTH_SALT
*  LOGGED_IN_SALT
*  NONCE_SALT

If the wp-config.php file is not writable, then the plugin will show the key / salt values on the plugin options page so the admin can then manually update the wp-config.php file.

== Installation ==

<i>Note: As is the case when installing any new plugin, it's always a good idea to backup your blog data before installing.</i>

1. After downloading the Update Unique Keys plugin, unpack and upload the file to the wp-content/plugins folder on your blog. Make sure to leave the directory structure of the archive intact so that all of the Update Unique Keys files are located in 'wp-content/plugins/updateuniquekeys/'
2. You will need to activate the Update Unique Keys plugin in order to update your wp-config.php file. Go to the Plugins tab and find Update Unique Keys in the list and click <strong>Activate</strong>.
3. After activating proceed to the plugin settings page (under Settings > Update Unique Keys) to update your keys and wp-config.php file.
4. You will be automatically logged out, due to the keys changing, simply re-login with the same username and password.

== Frequently Asked Questions ==

= Does this plugin mess up my wp-config.php file? =

No, every other line does not get touched.

= What if my wp-config.php file is not-writable? =

The plugin will display the values for you to manually update the wp-config.php file, using FTP or whatever method is allowed by your webhost.

= What if the keys are already generated and I run this plugin? =

The plugin will generate a new set of keys.  The only thing it does is log you out temporarily.  By updating the keys on occassion, provides more security for Wordpress.

== Screenshots ==

1. Output when wp-config.php is not writeable.
2. Output when successful.

== Changelog ==

= 1.0.0. =
* Original Release.

= 1.0.1 =
* Fix file include error

= 1.0.2 =
* Correctly tagging file versions
* Added more FAQ

= 1.0.3 =
* Correctly tagging file versions

= 1.0.4 =
* Cleaned up variables and class, make it not possible for namespace collisions with other plugins
* Added security, including administrator level user check and is_admin check

= 1.0.5 =
* Added localization
* Cleaned up admin dash panel

= 1.0.6 =
* no functional changes
* changed links in admin options panel

= 1.0.7 =
* no functional changes
* verified works in 3.0

= 1.0.8 =
* moved pulling keys from wordpress.org to update function
* to prevent site timeout if wordpress.org is timing out

= 1.0.9 =
* supporting up to Wordpress 3.9

= 1.0.10 =
* supporting up to Wordpress 4.5.3

= 1.0.11 =
* supporting up to Wordpress 6.0
* added some logic checking based on support feedback about empty keys
* switched to using php curl (but checks if enabled in the code)
* and more error checking

== Upgrade Notice ==

= 1.0.0 =
No upgrade, original release.

= 1.0.1 =
Ok to upgrade files, no need to delete first

= 1.0.2 =
Ok to upgrade files, no need to delete first

= 1.0.3 =
Ok to upgrade files, no need to delete first

= 1.0.4 =
Ok to upgrade files, no need to delete first

= 1.0.5 =
Ok to upgrade files, no need to delete first

= 1.0.6 =
Ok to upgrade files, no need to delete first

= 1.0.7 =
Ok to upgrade files, no need to delete first

= 1.0.8 =
Ok to upgrade files, no need to delete first

= 1.0.9 =
Ok to upgrade files, no need to delete first

= 1.0.10 =
Ok to upgrade files, no need to delete first

= 1.0.11 =
Ok to upgrade files, no need to delete first