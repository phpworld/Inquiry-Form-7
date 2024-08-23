<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class PIF7_Meta_Box {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box_data'));
    }

    public function add_meta_box() {
        add_meta_box(
            'pif7_cf7_meta_box',
            __('Contact Form 7 Shortcode', 'product-inquiry-form-7'),
            array($this, 'render_meta_box'),
            'product',
            'side',
            'high'
        );
    }

    public function render_meta_box($post) {
        // Get the existing shortcode value
        $cf7_shortcode = get_post_meta($post->ID, '_pif7_cf7_shortcode', true);
        
        // Get all Contact Form 7 forms
        $forms = get_posts(array(
            'post_type' => 'wpcf7_contact_form',
            'posts_per_page' => -1
        ));
        
        echo '<label for="pif7_cf7_shortcode">' . __('Select Contact Form 7 Form', 'product-inquiry-form-7') . '</label>';
        echo '<select name="pif7_cf7_shortcode" id="pif7_cf7_shortcode">';
        echo '<option value="">' . __('Select a form', 'product-inquiry-form-7') . '</option>';
        
        foreach ($forms as $form) {
            echo '<option value="[contact-form-7 id=&quot;' . $form->ID . '&quot; title=&quot;' . $form->post_title . '&quot;]"' . selected($cf7_shortcode, '[contact-form-7 id="' . $form->ID . '" title="' . $form->post_title . '"]', false) . '>' . $form->post_title . '</option>';
        }
        
        echo '</select>';
    }

    public function save_meta_box_data($post_id) {
        if (isset($_POST['pif7_cf7_shortcode'])) {
            update_post_meta($post_id, '_pif7_cf7_shortcode', $_POST['pif7_cf7_shortcode']);
        }
    }
}

new PIF7_Meta_Box();
