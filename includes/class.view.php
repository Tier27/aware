<?php
namespace aware;

Class View {

	public static function make($view, $atts = array())
	{
		extract($atts);
		include(AWARE_PATH . "views/$view.php");
	}

}
