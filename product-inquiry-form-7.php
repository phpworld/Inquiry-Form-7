<?php
/*
Plugin Name: Product Inquiry Form 7
Description: Adds a Contact Form 7 inquiry form to WooCommerce product pages with a customizable form for each product.
Version: 1.0.0
Author: Your Name
Text Domain: product-inquiry-form-7
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Include the meta box class
include_once plugin_dir_path(__FILE__) . 'includes/class-pif7-meta-box.php';

// Enqueue necessary scripts and styles
add_action('wp_enqueue_scripts', 'pif7_enqueue_scripts');
function pif7_enqueue_scripts() {
    wp_enqueue_style('pif7-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
}

// Add the form to the WooCommerce product page
add_action('woocommerce_single_product_summary', 'pif7_woocommerce_cf7_single_product', 30);
function pif7_woocommerce_cf7_single_product() {
    global $post;
    
    // Get the saved Contact Form 7 shortcode
    $cf7_shortcode = get_post_meta($post->ID, '_pif7_cf7_shortcode', true);
    
    if ($cf7_shortcode) {
        echo '<button type="submit" id="trigger_cf" class="single_add_to_cart_button button alt">Inquiry Now</button>';
        echo '<div id="product_inq" style="display:none">';
        echo do_shortcode($cf7_shortcode);
        echo '</div>';
    }
}

// Add the jQuery script to handle the form display
add_action('woocommerce_single_product_summary', 'pif7_on_click_show_cf7_and_populate', 40);
function pif7_on_click_show_cf7_and_populate() {
    ?>
    <script type="text/javascript">
        jQuery('#trigger_cf').on('click', function(){
            if ( jQuery(this).text() == 'Inquiry Now' ) {
                jQuery('#product_inq').css("display","block");
                jQuery('input[name="your-subject"]').val('<?php the_title(); ?>');
                jQuery("#trigger_cf").html('Close'); 
            } else {
                jQuery('#product_inq').hide();
                jQuery("#trigger_cf").html('Inquiry Now'); 
            }
        });
    </script>
    <?php 
}
