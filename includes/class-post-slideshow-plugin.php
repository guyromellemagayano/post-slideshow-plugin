<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 * @author     Guy Romelle Magayano <guy@blueskyroi.com>
 */
class Post_Slideshow_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Post_Slideshow_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'POST_SLIDESHOW_PLUGIN_VERSION' ) ) {
			$this->version = POST_SLIDESHOW_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'post-slideshow-plugin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Post_Slideshow_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Post_Slideshow_Plugin_i18n. Defines internationalization functionality.
	 * - Post_Slideshow_Plugin_Admin. Defines all hooks for the admin area.
	 * - Post_Slideshow_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-slideshow-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-slideshow-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-post-slideshow-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-post-slideshow-plugin-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-post-slideshow-plugin-post.php';

        $this->loader = new Post_Slideshow_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Post_Slideshow_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Post_Slideshow_Plugin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Post_Slideshow_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'post_slideshow_meta_boxes' );
        $this->loader->add_action( 'save_post', $plugin_admin, 'post_slideshow_meta_save' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'enqueue_editor' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu');
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        $this->loader->add_action( 'updated_option', $plugin_admin, 'flush_permalinks', 100, 3 );

        // Add Settings links to the plugin
        $plugin_basename = plugin_basename( plugin_dir_path(__DIR__) . 'post-slideshow' . '.php');
        $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

        $plugin_public = new Post_Slideshow_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

        // Register post types
        $this->loader->add_action('init', $plugin_public, 'register_post_types');

        // Enqueue styles and scripts
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // Post slideshow public-facing functions
        $this->loader->add_filter( 'the_content', $plugin_public, 'slideshow_page_content' );
        $this->loader->add_action( 'pre_get_posts', $plugin_public, 'slideshow_query_mod', 100, 1 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Post_Slideshow_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
    }

    /**
     * Returns the slide index
     *
     * @since   1.0.0
     * @return  int/boolean
     */
    public function get_slide_index() {

        $index = isset( $_GET['post-slide'] ) && is_numeric( $_GET['post-slide'] ) ? $_GET['post-slide'] : false;

        return $index;

    }

    /**
     * Generates the front-end slideshow markup
     *
     * @since   1.0.0
     * @return  string HTML
     */
    public function output_slideshow_markup( $post_id ) {

        global $post;

        $slideshow = new Post_Slideshow_Post( $post_id );
        $index = $this->get_slide_index();

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') :
            $page_url = "https";
        else :
            $page_url = "http";
        endif;

        $page_url .= "://";
        $page_url .= $_SERVER['HTTP_HOST'];
        $page_url .= $_SERVER['REQUEST_URI'];

        $output = '';

        $begin_class = $index ? 'post-slideshow-hide post-slideshow-begin-slideshow' : 'post-slideshow-begin-slideshow';

        $output .= '<div class="post-slideshow-begin-wrap">';
        $output .= '<button data-open="1" class="button ' . $begin_class . '">';
        $output .= __( 'Begin Slideshow', 'post-slideshow' ) . '&nbsp; <i class="fas fa-chevron-right"></i></button>';
        $output .= '</div>';

        if ( $slideshow->post_slides ) :
            $slide_count = count( $slideshow->post_slides );

            $output .= '<div class="post-slideshow-content-slides">';

            $i = 0;

            foreach ( $slideshow->post_slides as $post_slide ) :
                $i++;

                if ( $i == $index ) :
                    $hide = '';
                endif;

                $hide = $i == $index ? '' : 'post-slideshow-hide';
                $output .= '<div class="post-slideshow-slide ' . $hide . '" data-index="' . $i . '">';
                $output .= '<div class="post-slideshow-slide-featured-image">';

                $img_src = wp_get_attachment_image_src( $post_slide['post_slideshow_featured_image'], 'full', false );
                $img_srcset = wp_get_attachment_image_srcset( $post_slide['post_slideshow_featured_image'], 'full' );
                $alt = get_post_meta( $post_slide['post_slideshow_featured_image'], '_wp_attachment_image_alt', true );

                if ( empty( $alt ) ) :
                    $alt = $post_slide['post_slideshow_title'];
                endif;

                if ( $post_slide['post_slideshow_featured_image'] ) :
                    $attachment = get_post( $post_slide['post_slideshow_featured_image'] );
                    $image_caption = $attachment->post_excerpt;

                    $output .= '<img src="' . esc_url( $img_src[0] ) . '" srcset="' . esc_attr( $img_srcset ) . '" sizes="(max-width: 50em) 87vw, 680px" alt="' . $alt . '" />';

                    if ( $image_caption ) :
                        $output .= '<figcaption class="wp-caption-text">' . $image_caption . '</figcaption>';
                    endif;
                endif;

                if ( $slide_count > $i && $i <= $slide_count - 1 ) :
                    $output .= '<button data-open="' . ($i + 1) . '" class="button post-slideshow-nav post-slideshow-slide-next">' . __( 'Next Slide', 'post-slideshow' ) . '&nbsp;<i class="fas fa-chevron-right"></i></button>';
                endif;

                $output .= '</div>';
                $output .= '<div class="post-slideshow-slide-title">';
                $output .= '<h3>' . $post_slide['post_slideshow_title'] . '</h3>';
                $output .= '<small>by <a href="' . get_author_posts_url( $post->post_author ) . '" rel="external">' . get_the_author_meta( 'display_name', $post->post_author ) . '</a></small>';
                $output .= '</div>';
                $output .= '<div class="post-slideshow-slide-description">' . $post_slide['post_slideshow_description'] . '</div>';
                $output .= '<div class="post-slideshow-share-slide">';
                $output .= '<ul class="post-slideshow-share-buttons">';

                $social_data = array(
                    'facebook' => array(
                        'title' => 'Facebook',
                        'icon'  => 'fab fa-facebook',
                        'class' => 'facebook',
                        'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $page_url,
                    ),
                    'twitter' => array(
                        'title' => 'Twitter',
                        'icon'  => 'fab fa-twitter',
                        'class' => 'twitter',
                        'url'   => 'https://twitter.com/intent/tweet?text=' . $page_title . '&amp;url=' . $page_url . '&amp;via=' . $twitter_username
                    ),
                    'mail'    => array(
                        'title' => 'Mail',
                        'icon'  => 'fas fa-envelope-open-text',
                        'class' => 'mail',
                        'url'   => 'mailto:' . $page_url
                    )
                );

                foreach( $social_data as $link ) :
                    $output .= '<li class="' . $link['class'] . '"><a href="' . $link['url'] . '" target="_blank"><i class="' . $link['icon'] . '"></i>&nbsp; ' . $link['title'] . '</a></li>';
                endforeach;

                $output .= '</ul>';
                $output .= '</div>';
                $output .= '<div class="post-slideshow-slide-footer">';
                $output .= '<div class="post-slideshow-button-wrap">';

                if ( $slide_count > $i && $i <= $slide_count - 1 ) :
                    $output .= '<button data-open="' . ($i - 1) . '" class="button post-slideshow-nav post-slideshow-slide-prev">&nbsp;<i class="fas fa-chevron-left"></i>' . __( 'Previous', 'post-slideshow' ) . '</button>';
                    $output .= '<button data-open="' . ($i + 1) . '" class="button post-slideshow-nav post-slideshow-slide-next">' . __( 'Next', 'post-slideshow' ) . '&nbsp;<i class="fas fa-chevron-right"></i></button>';
                else :
                    $output .= '<button data-open="' . ($i - 1) . '" class="button post-slideshow-nav post-slideshow-slide-prev">&nbsp;<i class="fas fa-chevron-left"></i>' . __('Previous', 'post-slideshow') . '</button>';
                    $output .= '<button data-open="' . ($i - $slide_count) . '" class="button post-slideshow-nav post-slideshow-slide-end">' . __('Return to Article', 'post-slideshow') . '&nbsp;<i class="fas fa-stop"></i></button>';
                endif;

                $output .= '</div>';
                $output .= '<div class="post-slideshow-slide-legend">';
                $output .= '<div>Page ' . $i . ' / <span class="slide-count">' . $slide_count . '</span></div>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';

            endforeach;

            $output .= '</div>';

        endif;

        return $output;

    }

    /**
     * Fetches the plugin options
     *
     * @since   1.0.0
     * @return  array Options array
     */
    public function get_options() {
        $options = get_option( 'Post_Slideshow_Plugin' );
        $force_reload = isset( $options['force_reload']) && ! empty( $options['force_reload'] ) ? true : false;
        $slug = isset( $options['base_slug'] ) && ! empty( $options['base_slug'] ) ? $options['base_slug'] : 'post-slideshow';
        $show_in_blog = isset( $options['show_in_blog'] ) && ! empty( $options['show_in_blog'] ) ? true : false;

        $return_options = array(
            'force_reload'  => $force_reload,
            'base_slug'     => $slug,
            'show_in_blog'  => $show_in_blog
        );

        return $return_options;
    }

}
