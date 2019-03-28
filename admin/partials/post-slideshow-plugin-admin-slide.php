<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://guyromellemagayano.netlify.com
 * @since      1.0.0
 *
 * @package    Post_Slideshow_Plugin
 * @subpackage Post_Slideshow_Plugin/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap post-slideshow-slide">
    <div class="meta-box-sortables">

        <?php

            wp_nonce_field( 'post_slideshow_plugin_meta', 'post_slideshow_plugin_meta_nonce' );

            $post_slides = get_post_meta( $post->ID, 'post_slideshow_slides', true );

            $i = 0;

            if ( $post_slides ) :

                foreach( $post_slides as $post_slide ) :

                    // Pre-increment post slides
                    $i++;
        ?>

                <div class="postbox closed ui-sortable-handle">
                    <button type="button" class="handlediv" aria-expanded="true">
                        <span class="screen-reader-text">Toggle panel</span>
                        <span class="toggle-indicator" aria-hidden="true"></span>
                    </button>

                    <h2 class="hndle ui-sortable" id="slide-preview"><i class="fas fa-ellipsis-v"></i>
                        <?php
                            if ($post_slide['post_slideshow_featured_image']):
                                $featured_img = wp_get_attachment_image_src($post_slide['post_slideshow_featured_image'], 'thumbnail');

                                if (isset($featured_img[0])):
                                    $url = $featured_img[0];
                        ?>

                        <img src="<?php echo $url; ?>" alt="" />

                        <?php
                                endif;
                            endif;
                        ?>

                        <strong data-update="post-slideshow-title"><?php echo $post_slide['post_slideshow_title']; ?></strong>
                    </h2>

                    <div class="inside">
                        <div class="form-group">
                            <label for="slide-title"><h4><?php esc_attr_e( 'Slide Title', 'post-slideshow' ); ?></h4></label>
                            <input type="text" class="large-text slide-title" name="post_slideshow_title[]" value="<?php echo $post_slide['post_slideshow_title']; ?>" data-bind="post-slideshow-title" />
                        </div>
                        <div class="form-group">
                            <label for="slide-featured-image"><h4><?php esc_attr_e( 'Featured Image', 'post-slideshow' ); ?></h4></label>
                            <button type="button" class="button post-slideshow-add-featured-image"><?php echo $post_slide['post_slideshow_featured_image'] ? __( 'Change Featured Image', 'post-slideshow' ) : __( 'Attach Featured Image', 'post-slideshow' ); ?></button>
                            <input type="hidden" class="slide-featured-image" name="post_slideshow_featured_image[]" value="<?php echo $post_slide['post_slideshow_featured_image'] ?>" />

                            <?php
                                if ( $post_slide['post_slideshow_featured_image'] ) :
                                    $featured_img = wp_get_attachment_image_src( $post_slide['post_slideshow_featured_image'], 'medium' );

                                    if ( isset( $featured_img[0] ) ) :
                                        $url = $featured_img[0];
                            ?>

                            <div class="post-slideshow-img-preview">
                                <div class="post-slideshow-img-preview--inner">
                                    <span class="dashicons dashicons-no delete-post-slideshow-img"></span>
                                    <img src="<?php echo $url; ?>" alt="" />
                                </div>
                            </div>

                            <?php
                                    endif;
                                endif;
                            ?>

                        </div>
                        <div class="form-group">
                            <label for="slide-description"><h4><?php esc_attr_e( 'Slide Description', 'post-slideshow' ); ?></h4></label>

                            <?php
                                $editor_settings = array(
                                    'media_buttons' => false,
                                    'textarea_name'	=> 'post_slideshow_description[]',
                                    'height'        => 300
                                );

                                wp_editor( 'slide_description_' . $i, $editor_settings );
                            ?>

                        </div>
                    </div>
                </div>

        <?php
                endforeach;
            endif;
        ?>

    </div>
</div>

<button type="button" class="button button-primary post-slideshow-add-slide"><?php echo __( 'Add Slide', 'post-slideshow' ); ?></button>
