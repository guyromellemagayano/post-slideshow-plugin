<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 * @author     Guy Romelle Magayano <guy@blueskyroi.com>
 */
class Post_Slideshow_Plugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        flush_rewrite_rules();
	}

}
