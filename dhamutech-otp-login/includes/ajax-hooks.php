<?php
add_action('wp_ajax_nopriv_dhamutech_verify_firebase_user', 'dhamutech_verify_firebase_user');

function dhamutech_verify_firebase_user() {
    $uid = sanitize_text_field($_POST['uid']);
    $phone = sanitize_text_field($_POST['phone']);

    $user = get_user_by('login', $uid);
    if (!$user) {
        $user_id = wp_create_user($uid, wp_generate_password(), $uid . '@dhamutech.com');
        update_user_meta($user_id, 'phone', $phone);
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
    } else {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
    }

    wp_send_json_success(['redirect' => '/secure-checkout-complete-your-home-décor']);
}
?>
