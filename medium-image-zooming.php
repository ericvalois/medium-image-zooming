<?php
/**
 * Medium Image Zooming
 *
 * @package   Medium Image Zooming
 * @author    Eric Valois
 * @license   GPL-2.0+
 * @link      https://github.com/ericvalois/medium-image-zooming
 * @copyright 2017 TTFB
 *
 * @wordpress-plugin
 * Plugin Name:       Medium Image Zooming
 * Plugin URI:        https://github.com/ericvalois/medium-image-zooming
 * Description:       WordPress plugin to bring Medium like image zooming effect.
 * Version:           1.0
 * Author:            Eric Valois
 * Author URI:        eric@ttfb.io
 * License:           GPLv2+
 * Text Domain:       medium-image-zooming
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/ericvalois/medium-image-zooming
 */

/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'medium_image_zooming_scripts', 1 );
function medium_image_zooming_scripts() {
    wp_enqueue_script( 'zooming', plugin_dir_url( __FILE__ ) . 'js/zooming.min.js', '', '1.0', true );
    wp_add_inline_script( 'zooming', '
        var customZooming = new Zooming({
            defaultZoomable: ".zoom-img",
            preloadImage: false,
            scaleBase: 0.8,
        });

        var customZooming = new Zooming({
            defaultZoomable: "figure img",
            preloadImage: false,
            scaleBase: 0.8,
        });
    ' );
}

add_filter( 'the_content', 'medium_image_zooming_add_class', 10 );
function medium_image_zooming_add_class( $content ){
    if( is_main_query() ){
        $pattern ="/<img(.*?)class=\"(.*?)\"(.*?)>/i";
        $replacement = '<img$1class="$2 zoom-img"$3>';
        $content = preg_replace($pattern, $replacement, $content);
        return $content;
    }else{
        return $content;
    }
    
}