<?php
/*
Plugin Name: DhamuTech OTP Login
Description: OTP-based login and registration using Firebase for WordPress and WooCommerce.
Version: 1.1
Author: DhamuTech
*/

defined('ABSPATH') || exit;

define('DHAMUTECH_OTP_PATH', plugin_dir_path(__FILE__));
define('DHAMUTECH_OTP_URL', plugin_dir_url(__FILE__));

require_once DHAMUTECH_OTP_PATH . 'includes/ajax-hooks.php';

function dhamutech_otp_enqueue_scripts() {
    wp_enqueue_script('firebase', 'https://www.gstatic.com/firebasejs/8.10.1/firebase.js', [], null, true);
    wp_enqueue_script('firebase-auth', 'https://www.gstatic.com/firebasejs/8.10.1/firebase-auth.js', [], null, true);
    wp_enqueue_script('otp-js', DHAMUTECH_OTP_URL . 'assets/js/otp.js', ['firebase', 'firebase-auth'], null, true);
    wp_localize_script('otp-js', 'dhamutech_otp_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
    wp_enqueue_style('otp-css', DHAMUTECH_OTP_URL . 'assets/css/style.css');
}
add_action('wp_enqueue_scripts', 'dhamutech_otp_enqueue_scripts');

function dhamutech_otp_shortcode() {
    ob_start();
    include DHAMUTECH_OTP_PATH . 'templates/otp-form.php';
    return ob_get_clean();
}
add_shortcode('dhamutech_otp_form', 'dhamutech_otp_shortcode');

function dhamutech_otp_redirect_unauthenticated() {
    if (is_page(['view-cart-and-checkout-decor-items', 'secure-checkout-complete-your-home-décor']) && !is_user_logged_in()) {
        wp_redirect(site_url('/login-via-otp'));
        exit;
    }
}
add_action('template_redirect', 'dhamutech_otp_redirect_unauthenticated');
?>
