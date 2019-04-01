<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/admin
 * @author     Guy Romelle Magayano <guy@blueskyroi.com>
 */
class Post_Slideshow_Plugin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

    /**
     * Add settings action link to the plugins page
     *
     * @since   1.0.0
     */
    public function add_action_links( $links ) {

        /**
         * Documentation:https: //codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
         */
        $settings_link = array(
            '<a href="' . admin_url( 'edit.php?post_type=post-slideshow&page=' . POST_SLIDESHOW_PLUGIN_NAME . '-settings' ) . '">' . __( 'Settings', POST_SLIDESHOW_PLUGIN_NAME ) . '</a>'
        );
        return array_merge( $settings_link, $links );

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since   1.0.0
     */
    public function add_plugin_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=post-slideshow',
            __( 'Plugin Settings', POST_SLIDESHOW_PLUGIN_NAME ),
            __( 'Plugin Settings', POST_SLIDESHOW_PLUGIN_NAME ),
            'manage_options',
            POST_SLIDESHOW_PLUGIN_NAME . '-settings',
            array( $this, 'display_plugin_settings' )
        );
    }

    /**
     * Registers the post meta boxes
     *
     * @since   1.0.0
     */
    public function post_slideshow_meta_boxes() {
        add_meta_box( 'post-slideshow-meta-box', __( 'Post Slideshow Details', 'post-slideshow' ), array( $this, 'post_slideshow_meta_callback' ), 'post-slideshow', 'normal', 'high' );
    }

    /**
     * Registers meta box callback for this plugin
     *
     * @since   1.0.0
     */
    public function post_slideshow_meta_callback( $post ) {

        wp_nonce_field( 'post_slideshow_plugin_meta', 'post_slideshow_plugin_meta_nonce' );

        $post_slides = get_post_meta( $post->ID, 'post_slideshow_slides', true );

        $i = 0;
        $output = '';

        echo '<div class="wrap post-slideshow-slide">';
        echo '<div class="meta-box-sortables">';

        if ( $post_slides ) :
            foreach( $post_slides as $post_slide ) :

                // Pre-increment post slides
                $i++;

                echo '<div class="postbox closed ui-sortable-handle">';
                echo '<button type="button" class="handlediv" aria-expanded="true">';
                echo '<span class="screen-reader-text">Toggle panel</span>';
                echo '<span class="toggle-indicator" aria-hidden="true"></span>';
                echo '</button>';
                echo '<h2 class="hndle ui-sortable" id="slide-preview"><i class="fas fa-ellipsis-v"></i>';

                if ($post_slide['post_slideshow_featured_image']):
                    $featured_img = wp_get_attachment_image_src($post_slide['post_slideshow_featured_image'], 'thumbnail');

                    if (isset($featured_img[0])):
                        $url = $featured_img[0];

                        echo '<img src="' . $url . '" alt="" />';
                    endif;
                endif;

                echo '<strong data-update="post-slideshow-title">' . $post_slide['post_slideshow_title'] . '</strong></h2>';
                echo '<div class="inside">';
                echo '<div class="form-group">';
                echo '<label for="slide-title"><h4>Slide Title</h4></label>';
                echo '<input type="text" class="large-text slide-title" name="post_slideshow_title[]" value="' . $post_slide['post_slideshow_title'] . '" data-bind="post-slideshow-title" />';
                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="slide-featured-image"><h4>Featured Image</h4></label>';
                
                $btn_text = $post_slide['post_slideshow_featured_image'] ? __('Change Featured Image', 'post-slideshow') : __('Attach Featured Image', 'post-slideshow');

                echo '<button type="button" class="button post-slideshow-add-featured-image">' . $btn_text . '</button>';
                echo '<input type="hidden" class="slide-featured-image" name="post_slideshow_featured_image[]" value="' . $post_slide['post_slideshow_featured_image'] .'" />';

                if ( $post_slide['post_slideshow_featured_image'] ) :
                    $featured_img = wp_get_attachment_image_src( $post_slide['post_slideshow_featured_image'], 'medium' );

                    if ( isset( $featured_img[0] ) ) :
                        $url = $featured_img[0];

                        echo '<div class="post-slideshow-img-preview">';
                        echo '<div class="post-slideshow-img-preview--inner">';
                        echo '<span class="dashicons dashicons-no delete-post-slideshow-img"></span>';
                        echo '<img src="' . $url . '" alt="" />';
                        echo '</div>';
                        echo '</div>';
                    endif;
                endif;

                echo '</div>';
                echo '<div class="form-group">';
                echo '<label for="slide-description"><h4>Slide Description</h4></label>';

                $editor_settings = array(
                    'media_buttons' => false,
                    'editor_height' => 350,
                    'textarea_name'	=> 'post_slideshow_description[]'
                );

                wp_editor( $post_slide['post_slideshow_description'], 'slide_description_' . $i, $editor_settings );

                echo '</div>';
                echo '</div>';
                echo '</div>';

            endforeach;
        endif;

        echo '</div>';
        echo '</div>';
        echo '<button type="button" class="button post-slideshow-add-slide">' . __( 'Add Slide', 'post-slideshow' ) . '</button>';

    }

    /**
     * Save the custom slideshow metadata
     *
     * @since   1.0.0
     */
    public function post_slideshow_meta_save( $post_id ) {

      if ( ! isset( $_POST['post_slideshow_plugin_meta_nonce'] ) ) :
        return;
      endif;

      if ( ! wp_verify_nonce( $_POST['post_slideshow_plugin_meta_nonce'], 'post_slideshow_plugin_meta' ) ) :
        return;
      endif;

      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
        return;
      endif;

      if ( ! current_user_can( 'edit_post', $post_id ) ) :
        return;
      endif;

      $titles = array_filter( $_POST['post_slideshow_title'] );
      $featured_images = $_POST['post_slideshow_featured_image'];
      $descriptions = $_POST['post_slideshow_description'];

      $post_slides = array();

      $i = 0;

      foreach ( $titles as $k => $v ) :
        $r = array();
        $r['post_slideshow_title'] = $v;
        $r['post_slideshow_featured_image'] = esc_attr( $featured_images[$i] );
        $r['post_slideshow_description'] = wp_kses_post( $descriptions[$i] );

        $post_slides[] = $r;

        $i++;

      endforeach;

      $data = array(
        'post_slideshow_slides' => $post_slides
      );

      foreach( $data as $k => $v ) :
        update_post_meta( $post_id, $k, $v );
      endforeach;

    }

    /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Slideshow_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Slideshow_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

        wp_enqueue_style( $this->plugin_name . '-font-awesome', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dist/css/post-slideshow-plugin-admin.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Post_Slideshow_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Post_Slideshow_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

      wp_enqueue_media();
      wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dist/js/post-slideshow-plugin-admin.min.js', array( 'jquery', 'editor' ), $this->version, true );

    }

    /**
     * Enqueue default WordPress WYSIWYG editor
     *
     * @since   1.0.0
     */
    public function enqueue_editor() {

        wp_enqueue_editor();

    }

    /**
     * Render the settings page for this plugin
     *
     * @since   1.0.0
     */
    public function display_plugin_settings() {

        include_once 'partials/post-slideshow-plugin-admin-settings.php';

    }

    /**
	 * Validates the options data
	 *
	 * @since    1.0.0
	 */
	public function validate_options( $input ) {

        $plugin = new Post_Slideshow_Plugin();
        $options = $plugin->get_options();

        $input['force_reload'] = isset( $input['force_reload'] ) ? true : false;
        $input['show_in_blog'] = isset( $input['show_in_blog'] ) ? true : false;

		return $input;

    }

    /**
     * Register the settings
     *
     * @since   1.0.0
     */
    public function register_settings() {

        register_setting( 'Post_Slideshow_Plugin_options', 'Post_Slideshow_Plugin', array( $this, 'validate_options' ) );

    }

    /**
     * Sets a transient on option update to later trigger a rewrite flush
     *
     * @since   1.0.0
     */
    public function flush_permalinks( $option, $old_value, $value ) {

        set_transient( 'post_slideshow_flush', true );

    }

}
