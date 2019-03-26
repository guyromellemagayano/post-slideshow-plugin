<?php

/**
 * Defines the Slideshow Post class for this plugin
 *
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 */

/**
 * @since      1.0.0
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/includes
 * @author     Guy Romelle Magayano <guy@blueskyroi.com>
 */
class Post_Slideshow_Post {

  public $ID;
  public $name;
  public $excerpt;
  public $post_slides;

  public function __construct( $post_id ) {
    $post = get_post( $post_id );

    $this->ID = $post->ID;
    $this->name = $post->post_title;
    $excerpt = $post->post_excerpt;
    $this->excerpt = empty( $excerpt ) ? wp_trim_words( $post->post_content, 55 ) : $excerpt;

    $post_slides = get_post_meta( $post_id, 'post_slideshow_slides', true ) ? get_post_meta( $post_id, 'post_slideshow_slides', true ) : false;
    $this->post_slides = $post_slides;
  }

}
