<?php
namespace aware;

class Thread extends \stdClass {

	public function __construct($atts = array())
	{
		if( empty($atts) ) $atts = $_POST;
		$this->sender = $atts['sender'];
		$this->recipient = $atts['recipient'];
		$this->subject = stripslashes($atts['subject']);
		if( isset($atts['inbound']) )
			$this->inbound = $atts['inbound'];
		else
			$this->inbound = $atts['recipient'];
		if( isset($atts['outbound']) )
			$this->outbound = $atts['outbound'];
		else 
			$this->outbound = $atts['sender'];
	}

	public function insert()
	{
		global $wpdb;
		$wpdb->insert('wp_aware_threads', (array)$this);
		$this->id = $wpdb->insert_id;
	}

	public static function create($data = array())
	{
		$thread = new Thread($data);
		$thread->insert();
		return $thread;
	}

	public static function all()
	{
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM wp_aware_threads");
	}

	public static function result($query)
	{
		global $wpdb;
		$_ = $wpdb->get_results($query);
		return static::convert($_);
	}

	public static function inbox($read = true)
	{
		global $wpdb;
		$recipient = get_current_user_id();
		$query = "SELECT * FROM wp_aware_threads WHERE inbound = $recipient ";
		if( $read == false ) $query .= " AND `read` IS NULL ";
		$query .= " ORDER BY created_at DESC";
		$_ = $wpdb->get_results($query);
		return static::convert($_);
	}

	public static function unread()
	{
		$recipient = get_current_user_id();
		$query = "SELECT * FROM wp_aware_threads t JOIN wp_aware_messages m ON t.id = m.thread WHERE m.recipient = $recipient AND m.read IS NULL GROUP BY thread";
		return static::result($query);
	}

	public static function outbox()
	{
		global $wpdb;
		$sender = get_current_user_id();
		return $wpdb->get_results("SELECT * FROM wp_aware_threads WHERE outbound = $sender ORDER BY created_at DESC");
		return $wpdb->get_results("SELECT * FROM wp_aware_threads WHERE (sender = $sender OR recipient = $sender) AND inbound != $sender ORDER BY created_at DESC");
	}

	public static function find($id)
	{
		global $wpdb;
		$query = " SELECT * FROM wp_aware_threads t ";
		$query .= " JOIN wp_users u ON t.sender = u.ID ";
		$query .= " WHERE t.id = $id ";
		$row = $wpdb->get_row($query, "ARRAY_A");
		$thread = static::cast($row);
		$thread->messages = $thread->getMessages();
		$thread->with('sender', 'wp_users', 'ID');
		$thread->with('recipient', 'wp_users', 'ID');
		return $thread;
	}

	public function date()
	{
		return date('M d, Y', strtotime($this->created_at));
	}

	public static function cast($atts)
	{
		if( !is_array( $atts ) ) $atts = (array)$atts;
		$_ = new Thread($atts);
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

	public function with($local_key, $foreign_table, $foreign_key)
	{
		global $wpdb;
		$key = $this->$local_key;
		$query = "SELECT * FROM $foreign_table WHERE $foreign_key = $key";
		$this->$local_key = $wpdb->get_row($query);
		return $this;
	}

	public function getMessages()
	{
		global $wpdb;
		$query = "SELECT * FROM wp_aware_messages WHERE thread=$this->id ORDER BY created_at DESC";
		$messages = $wpdb->get_results($query);
		return Message::convert($messages);
	}

	public function party($id)
	{
		if( $this->sender->ID == $id ) 
			return $this->sender;
		else
			return $this->recipient;
	}

	public function otherParty($id)
	{
		if( $this->sender->ID == $id ) 
			return $this->recipient;
		else
			return $this->sender;
	}

	public function getPermalink()
	{
		return bloginfo('wpurl') . '/client/thread/' . $this->id;
	}

	public function _update($key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_threads SET $key='$value' WHERE id=$this->id";
		$wpdb->query($query);
		return $this;
	}

	public static function update($id, $key, $value)
	{
		global $wpdb;
		$query = "UPDATE wp_aware_threads SET $key='$value' WHERE id=$id";
		$wpdb->query($query);
	}

	public function setInbound($id)
	{
		$this->_update('inbound', $id);
		return $this;
	}

	public function setOutbound($id)
	{
		$this->_update('outbound', $id);
		return $this;
	}

	public static function deactivate($id)
	{
		global $wpdb;
		$thread = Thread::find($id);
		if( $thread->inbound == get_current_user_id() )
		{
			$key = 'inbound';
		} else {
			$key = 'outbound';
		}
		echo $key;
		$thread->_update($key, 0);
		die;
	}

	public function isActive()
	{
		return ( $this->inbound == get_current_user_id() || $this->outbound == get_current_user_id() );
	}

	public function markAsRead()
	{
		$this->read = true;
		$this->_update('`read`', $this->read);
		$recipient = get_current_user_id();
		global $wpdb;
		$query = "UPDATE wp_aware_messages SET `read` = TRUE WHERE thread = $this->id AND recipient = $recipient";
		$wpdb->query($query);
	}

}

?>
