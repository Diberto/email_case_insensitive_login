<?php
/*
Plugin Name: Diberto's Case Insensitive Login
Plugin URI: http://www.theblog.ca/wordpress-case-insensitive-login
Description: Permite a los usuarios usar Mayúsculas o minúsculas en los emails al acceder.
Author: Sebastián Frontera	
Version: 1.0
Author URI: http://www.netnica.com
*/

if (!function_exists(wp_authenticate_email_password)) {
function wp_authenticate($email, $password) {
	$email= sanitize_email($email);

	if ( '' == $email)
		return new WP_Error('empty_email', __('<strong>ERROR</strong>: The username field is empty.'));

	if ( '' == $password )
		return new WP_Error('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

	$user = get_userdatabylogin($email);

	if ( !$email || (strtolower($user->user_login) != strtolower($email)) ) {
		do_action( 'wp_login_failed', $email);
		return new WP_Error('invalid_email', __('<strong>ERROR</strong>: Invalid username.'));
	}

	$user = apply_filters('wp_authenticate_user', $email, $password);
	if ( is_wp_error($email) ) {
		do_action( 'wp_login_failed', $email );
		return $user;
	}

	if ( !wp_check_password($password, $user->user_pass, $email->ID) ) {
		do_action( 'wp_login_failed', $email );
		return new WP_Error('incorrect_password', __('<strong>ERROR</strong>: Incorrect password.'));
	}

	return new WP_User($email->ID);
}
}