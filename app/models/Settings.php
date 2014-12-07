<?php
namespace aware;

class Settings {

	public static function named($name)
	{
		return get_option($name);
	}

	public static function adminID()
	{
		return static::named('aware_administrator');
	}

	public static function admin()
	{
		return get_user(static::adminID());
	}

	public static function durations()
	{
        ?>
		<input type="radio" name="duration" value="1"><label>Half day (7-11)</label>
		<input type="radio" name="duration" value="2"><label>All day (7-3)</label>
		<input type="radio" name="duration" value="3"><label>Half day (9-1:30)</label>
        <input type="radio" name="duration" value="4"><label>All day (9-6)</label>
        <input type="radio" name="duration" value="5"><label>Custom</label>
		<?php

	}
		
}

?>
