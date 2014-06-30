<?php
add_action( 'admin_post_add_foobar', 'prefix_admin_add_foobar' );

function prefix_admin_add_foobar() {
	extract( $_POST );
	update_post_meta('123', 'rand', rand());
	$subject = 'From AWARE';
	$headers = 'From: AWARE <aware@tier27.com>' . "\r\n";
	wp_mail( $to, $subject, $message, $headers );
	wp_redirect( $_SERVER['HTTP_REFERER'] );
}
?>
