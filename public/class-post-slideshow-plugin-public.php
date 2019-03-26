<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/public
 * @author     Guy Romelle Magayano <guy@blueskyroi.com>
 */
class Post_Slideshow_Plugin_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dist/css/post-slideshow-plugin-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

        $plugin = new Post_Slideshow_Plugin();
        $options = $plugin->get_options();

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'dist/js/post-slideshow-plugin-public.min.js', array( 'jquery' ), $this->version, true );

        $data = array(
            'plugin_path'   => plugin_dir_url( __FILE__ ),
            'ajaxurl'       => admin_url( 'admin-ajax.php' ),
            'options'       => $options
        );

        wp_localize_script( $this->plugin_name, 'PostSlideshow', $data );

    }

    /**
     * Register the main post type for this plugin
     *
     * @since   1.0.0
     */
    public function register_post_types() {

        $plugin = new Post_Slideshow_Plugin();
        $options = $plugin->get_options();

        register_post_type(
            'post-slideshow',
            array(
                'labels'                => array(
                    'name'                  => _x( 'Post Slideshows', 'Post type general name', 'post-slideshow' ),
                    'singular_name'         => _x( 'Post Slideshow', 'Post type singular name', 'post-slideshow' ),
                    'menu_name'             => _x( 'Post Slideshows', 'Admin menu', 'post-slideshow' ),
                    'name_admin_bar'        => _x( 'Post Slideshow', 'Add new on admin bar', 'post-slideshow' ),
                    'add_new'               => _x( 'Add New', 'post-slideshow', 'post-slideshows' ),
                    'add_new_item'          => _x( 'Add New Post Slideshow', 'post-slideshow' ),
                    'new_item'              => _x( 'New Post Slideshow', 'post-slideshow' ),
                    'edit_item'             => _x( 'Edit Post Slideshow', 'post-slideshow' ),
                    'view_item'             => _x( 'View Post Slideshow', 'post-slideshow' ),
                    'all_items'             => _x( 'All Post Slideshows', 'post-slideshow' ),
                    'search_items'          => _x( 'Search Post Slideshows', 'post-slideshow' ),
                    'parent_item_colon'     => _x( 'Parent Post Slideshows:', 'post-slideshow' ),
                    'not_found'             => _x( 'No Post Slideshows Found', 'post-slideshow' ),
                    'not_found_in_trash'    => _x( 'No Post Slideshows Found in Trash', 'post-slideshow' )
                ),
                'description'           => __( 'Description', 'post-slideshow' ),
                'menu_icon'             => 'dashicons-format-video',
                'public'                => true,
                'publicly_queryable'    => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'query_var'             => true,
                'rewrite'               => array( 'slug' => $options['base_slug'] ),
                'capability_type'       => 'post',
                'has_archive'           => true,
                'hierarchical'          => false,
                'menu_position'         => 5,
                'taxonomies'            => array( 'category', 'post_tag' ),
                'supports'              => array(
                    'title',
                    'editor',
                    'author',
                    'comments',
                    'thumbnail',
                    'excerpt'
                )
            )
        );

        // Flushes permalinks if slug-change transient has been set
        if ( delete_transient( 'post_slideshow_flush' ) ) :
            flush_rewrite_rules();
        endif;

    }

    /**
     * Appends slideshow content to the slideshow template output
     *
     * @since   1.0.0
     * @return  string post content
     */
    public function slideshow_page_content( $content ) {

        global $post;

        $plugin = new Post_Slideshow_Plugin();

        if ( $post->post_type == 'post-slideshow' ) :
            if ( ! is_admin() && is_singular() && is_main_query() ) :
                $slideshow = $plugin->output_slideshow_markup( $post->ID );
                $index = $plugin->get_slide_index();
                $wrapper_class = $index ? 'post-slideshow-hide post-slideshow-overview' : 'post-slideshow-overview';
                $content = '<div class="' . $wrapper_class . '">' . $content . '</div>' . $slideshow;
            endif;
        endif;

        return $content;
    }

    /**
     * Modifies the query if slideshow posts are included in blog feeds
     *
     * @since   1.0.0
     */
    public function slideshow_query_mod( $query ) {
        $plugin = new Post_Slideshow_Plugin();
        $options = $plugin->get_options();

        if ( $options['show_in_blog'] == true ) :
            if ( ! is_admin() && $query->is_main_query() ) :
                if ( $query->is_feed() || $query->is_archive() || $query->is_search() || $query->is_posts_page() || $query->is_home() ) :
                    if ( ! is_post_type_archive() ) :
                        $query->set( 'post_type', array( 'post-slideshow', 'post' ) );
                    endif;
                endif;
            endif;
        endif;
    }

}
