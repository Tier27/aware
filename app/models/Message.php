<?php
namespace aware;

class Message {

	public function __construct($atts = array(), $casting = false)
	{
		if( empty($atts) ) $atts = $_POST;
		$this->sender = $atts['sender'];
		$this->recipient = $atts['recipient'];
		$this->content = stripslashes($atts['content']);
		if( !isset($atts['thread'] ) )
		{
			$thread = Thread::create($atts);
			$this->thread = $thread->id;
		} else {
			$this->thread = $atts['thread'];
			if( !$casting )
			{
				Thread::update($this->thread, 'inbound', $this->recipient);
				Thread::update($this->thread, 'outbound', $this->sender);
			}
		}
	}

	public function insert()
	{
		global $wpdb;
		$wpdb->insert('wp_aware_messages', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($data = array())
	{
		$message = new Message($data);
		$message->insert();
		return $message;
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$_ = new Message($atts, true);
		$_->id = $atts['id'];
		$_->created_at = $atts['created_at'];
		return $_;
	}

	public static function convert($__)
	{
		$___ = array();
		foreach( $__ as $_ ){
			$___[] = static::cast($_);
		}
		return $___;
	}

	public function getPermalink()
	{
		return site_url("/client/thread/$this->thread");
	}

		
}

?>
