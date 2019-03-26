<?php

/**
 * Fired during plugin activation
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 * @author     Guy Romelle Magayano <guy@blueskyroi.com>
 */
class Post_Slideshow_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        $options = get_option('Post_Slideshow_Plugin');

        if ( ! $options ) :

            $defaults = array(
                'force_reload'  => false,
                'show_in_blog'  => true
            );

            update_option( 'Post_Slideshow_Plugin', $defaults );

        endif;

        flush_rewrite_rules();
	}

}
