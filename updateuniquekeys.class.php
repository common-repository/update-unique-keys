<?php
/*
Copyright 2010  BRIAN_PASSAVANTI  (email : gottaloveit@gmail.com)

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
class updateuniquekeys
{
	private $name;
	private $filename;
	private $isWritable = false;
	private $originalFileArray = array();
	private $hidden_field_name;
	private $hidden_field_value;
	private $nonce_action;
	private $pluginTitle;
	private $localeDir;
	private $localeRelDir;
	private $keys;
    private $keyFile = 'http://api.wordpress.org/secret-key/1.1/salt';
	private $keysExpected = array('AUTH_KEY','SECURE_AUTH_KEY','LOGGED_IN_KEY','NONCE_KEY','AUTH_SALT','SECURE_AUTH_SALT','LOGGED_IN_SALT','NONCE_SALT');

	public function __construct($name,$localeDir,$localeRelDir) {
		$this->name = $name;
		$this->filename = ABSPATH . DIRECTORY_SEPARATOR . 'wp-config.php';
		$this->isWritable = is_writable($this->filename);
		$this->localeDir = $localeDir;
		$this->localeRelDir = $localeRelDir;
		add_action('init', array(&$this, 'loadLocale'));
		$this->hidden_field_name = 'uuk_submit_hidden';
		$this->hidden_field_value = 'uuksubmit';
		$this->nonce_action = 'updatewpconfig';
		$this->pluginTitle = "Update Unique Keys";
	}

	function updateuniquekeys($name,$localeDir,$localeRelDir) {
		$this->__construct($name,$localeDir,$localeRelDir);
	}

	public function loadLocale() {
		if (function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain($this->name,$this->localeDir,$this->localeRelDir);
		}
	}

	private function isCurl() {
		return function_exists('curl_version');
	}

	private function retrieveNewKeys() {

		$url = $this->keyFile;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);

		$output = curl_exec($ch);
		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($httpStatusCode != 200 || !(isset($output)) || $output == '') {
			return false;
		}

		foreach($this->keysExpected as $key) {
			if (strpos($output, $key) === false) {
				return false;
			}
		}

		$this->keys = explode("\n", $output);

		if (isset($this->keys) && is_array($this->keys) && count($this->keys) > 0) {
			return true;
		} else {
			return false;
		}
	}

	private function findLine($str) {
		foreach ($this->originalFileArray as $k=>$v) {
			$pos = strpos($v, $str);
			if ($pos !== false) {
				return $k;
				break;
			}
		}
		return false;
	}

	private function update() {
		$this->originalFileArray = file($this->filename);

		foreach ($this->keys as $k=>$v) {
			if (isset($v) && $v != '') {
				$pos = strpos($v, ",");
				$str = substr($v,8,$pos);
				$pos2 = strpos($str, "'");
				$str = substr($str,0,$pos2);
				$lineNum = $this->findLine($str);
				if ($lineNum !== false) {
					$this->originalFileArray[$lineNum] = $v;
				}
			}
		}

		ksort($this->originalFileArray);
		if (file_put_contents($this->filename,implode("\n", $this->originalFileArray)) === false) {
			return false;
		}
		return true;
	}

	private function outputIfNotWritable() {
		$output['body'] = '';
		$output['msg'] = '<div class="updated"><p><strong>'. __('Config File Not Writable.', 'update-unique-keys') .'</strong></p>
						  <p>'. sprintf(__("Manually edit the file %s in your blog root with the following:", 'update-unique-keys'), 'wp-config.php') .'</p>
						<pre>'. htmlspecialchars(implode($this->keys)) .'</pre></div>';
		return $output;
	}

	private function outputIfWritable() {
		$output['body'] = '';
		$output['msg'] = '<div class="updated"><p><strong>'. __('Config File Updated!', 'update-unique-keys') .'</strong></p>
						 <p>'. __('You have automatically been logged out, due to the new keys.  Your password is the same.', 'update-unique-keys') .'</p></div>';
		return $output;
	}

	private function outputError() {
		$output['body'] = '';
		$output['msg'] = '<div class="error"><p><strong>'. __('An error has occurred, please try again', 'update-unique-keys') .'</strong></p></div>';
		return $output;
	}

	private function outputNoCurl() {
		$output['body'] = '';
		$output['msg'] = '<div class="error"><p><strong>'. sprintf(__('The %s module does not seem to be loaded.  Please ask your hosting provider to enable it and then try again.', 'update-unique-keys'), 'PHP Curl') .'</strong></p></div>';
		return $output;
	}

	private function makeHTMLform() {
		$output['msg'] = '';
		$output['body'] = '<h4>'. sprintf(__("Press the button below to generate the keys and salts and update the %s file.", 'update-unique-keys'), 'wp-config.php') .'</h4>
					<form method="post" action="">';
		if (function_exists('wp_nonce_field')) {
			$output['body'] .= wp_nonce_field($this->name . '-'. $this->nonce_action);
		}
		$output['body'] .= '<input type="hidden" name="'.$this->hidden_field_name.'" value="'.$this->hidden_field_value.'" />
						<p class="submit">
							<input type="submit" class="button-primary" name="Submit" value="Update" />
						</p>
					</form>';
		return $output;
	}

	public function ActionAdminMenu() {
		add_options_page('Update Unique Keys', 'Update Unique Keys', 'activate_plugins', $this->name, array($this, 'GetConfigHTML'));
	}

	public function GetConfigHTML() {

		if (is_admin() && current_user_can('activate_plugins')) {

			if ((array_key_exists($this->hidden_field_name,$_POST)) && ($_POST[$this->hidden_field_name] == $this->hidden_field_value )) {
				if (function_exists('check_admin_referer')) {
					check_admin_referer($this->name . '-'. $this->nonce_action);
				}

				if (!$this->isCurl()) {
					$content = $this->outputNoCurl();
				} elseif (!$this->retrieveNewKeys()) {
					$content = $this->outputError();
				} elseif (!$this->isWritable) {
					$content = $this->outputIfNotWritable();
				} elseif (!$this->update()) {
					$content = $this->outputError();
				} else {
					$content = $this->outputIfWritable();
				}

			} else {
				$content = $this->makeHTMLform();
			}
			$output = $content['msg'];
			$output .= '<div class="wrap">
							<div id="icon-options-general" class="icon32"><br /></div>
							<h2>'. $this->pluginTitle .'</h2>

							<div id="poststuff" class="metabox-holder has-right-sidebar">
								<div class="inner-sidebar">
									<div class="meta-box-sortabless ui-sortable" style="position:relative;">
										<div id="uuk_sb_plugins" class="postbox">
											<h3 class="hndle"><span>'. __('Other Plugins', 'update-unique-keys') .'</span></h3>
											<div class="inside">
												<br />
												<p><a class="button-secondary" href="http://wordpress.org/extend/plugins/wp-zff-zend-framework-full/" title="WP-ZFF Zend Framework Full">WP-ZFF Zend Framework Full</a></p>
												<br />
											</div>
										 </div>
									</div>
									<div class="meta-box-sortabless ui-sortable" style="position:relative;">
										<div id="uuk_sb_donate" class="postbox">
											<h3 class="hndle"><span>'. __('Donate', 'update-unique-keys') .'</span></h3>
											<div class="inside"><h4>'. __('Donations appreciated, thank you!', 'update-unique-keys') . '</h4>
												<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
													<input type="hidden" name="cmd" value="_s-xclick">
													<input type="hidden" name="hosted_button_id" value="2HAFRNX23EE88">
													<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
													<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
												</form>
											</div>
										 </div>
									</div>
								</div>';
			if (!empty($content['body'])) {
			$output .= '		<div class="has-sidebar sm-padded" >
									<div id="post-body-content" class="has-sidebar-content">
										<div class="meta-box-sortabless">
											<div class="postbox">
												<h3 class="hndle"><span>'. $this->pluginTitle .'</span></h3>
												<div class="inside">'. $content['body'] .' </div>
											</div>
										</div>
									</div>
								</div>';
			}
			$output .= '	</div>
						</div>';
			echo $output;
		}
	}

}

