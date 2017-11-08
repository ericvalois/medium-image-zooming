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
            scaleBase: 0.5,
        })
    ' );
}

add_filter( 'the_content', 'medium_image_zooming_add_class', PHP_INT_MAX );
function medium_image_zooming_add_class( $content ){

    $medium_content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
    $medium_document = new DOMDocument();

    libxml_use_internal_errors(true);
    if( !empty($medium_content) ){
        $medium_document->loadHTML(utf8_decode($medium_content));
    }else{
        return;
    }


    $imgs = $medium_document->getElementsByTagName('img');
    foreach ($imgs as $img) {

        // Add Class
        $existing_class = $img->getAttribute('class');
        $img->setAttribute('class', "zoom-img $existing_class");

    }

    $html_fragment = preg_replace('/^<!DOCTYPE.+?>/', '', str_replace( array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $medium_document->saveHTML()));
    return $html_fragment;
    
}