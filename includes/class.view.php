<?php
namespace aware;

Class View {

	public static function make($view, $atts = array())
	{
		return static::template($view, $atts);
	}

	public static function template($view, $atts = array())
	{
		if( is_array($atts) ) extract($atts);
		include(AWARE_PATH . "app/views/$view.php");
	}

}
