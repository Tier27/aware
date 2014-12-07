<?php
namespace aware;

class api {
	public function __construct() {
		add_shortcode( 'aware', array( __CLASS__, 'shortcode' ) );		
	}
	public function shortcode() {
		return View::template('aware');
	}
}
