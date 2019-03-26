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
<?php
    $plugin = new Post_Slideshow_Plugin();
    $options = $plugin->get_options();

    $force_reload = $options['force_reload'];
    $base_slug = $options['base_slug'];
    $show_in_blog = $options['show_in_blog'];
?>

<div class="wrap">
    <h2><strong><?php _e( 'Post Slideshow Options Panel', POST_SLIDESHOW_PLUGIN_NAME ); ?></strong></h2>

    <form action="options.php" method="post">

        <?php settings_fields( 'Post_Slideshow_Plugin_options' ); ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">
                    <?php echo _e( 'Force page reload on Slide Navigation', POST_SLIDESHOW_PLUGIN_NAME ); ?>
                </th>
                <td>
                    <input type="checkbox" name="Post_Slideshow_Plugin[force_reload]" value="1" <?php echo checked( 1, $force_reload ); ?> />
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <?php echo _e( 'Slideshow URL Base', POST_SLIDESHOW_PLUGIN_NAME ); ?>
                </th>
                <td>
                    <input type="text" name="Post_Slideshow_Plugin[base_slug]" value="<?php echo $base_slug; ?>" />
                    <p class="description"><?php echo _e( 'If this slug is in use elsewhere, there could be a conflict. If no values are entered, the default slug (post-slideshow) will be used', POST_SLIDESHOW_PLUGIN_NAME ); ?></p>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row">
                    <?php echo _e( 'Show Slideshows in Blog Feed?', POST_SLIDESHOW_PLUGIN_NAME ); ?>
                </th>
                <td>
                    <input type="checkbox" name="Post_Slideshow_Plugin[show_in_blog]" value="1" <?php echo checked( 1, $show_in_blog ); ?> />
                    <p class="description"><?php echo _e( 'If enabled, slideshow posts will appear next to blog posts in your main content loop', POST_SLIDESHOW_PLUGIN_NAME ); ?></p>
                </td>
            </tr>
        </table>

        <?php submit_button( 'Save All Changes', 'button-primary', 'submit', true ); ?>

    </form>
</div>
