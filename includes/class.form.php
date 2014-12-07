<?php
namespace aware;

class form {

	public function textarea( $args = array() ) { 
	 	$defaults = array(
			'label'		=> 'label',
			'name'		=> 'name',
			'placeholder'	=> 'placeholder',
			'value'		=> 'value'
		);
		$args = array_merge( $defaults, $args );
	?>
	  <div class="row">
	    <div class="large-12 columns">
	      <label><?php self::text( $args['label'] ); ?>
		<textarea <?php self::name( $args['name'] ); ?> <?php self::placeholder( $args['placeholder'] ); ?>><?php self::text( $args['value'] ); ?></textarea>
	      </label>
	    </div>
	  </div>
	<?php }

	public function checkboxes( $args ) {
	 	$defaults = array(
			'label'		=> 'label',
			'name'		=> 'name',
			'placeholder'	=> 'placeholder',
			'values'	=> array(),
			'checked'	=> array()
		);
		$args = array_merge( $defaults, $args );
	?>
	  <div class="row">
	    <div class="large-12 columns">
	      <label><?php self::text( $args['label'] ); ?></label>
	      <?php foreach( $args['values'] as $input ) : ?>
	      <?php endforeach; ?>
	    </div>
	  </div>
	<?php }

	public function text( $text ) {
		if( $text != '' ) echo $text;
	}

	public function name( $name ) {
		if( $name != '' ) echo "name='$name'";
	}

	public function placeholder( $placeholder ) {
		if( $placeholder != '' ) echo "placeholder='$placeholder'";
	}

	public function value( $value ) {
		if( $value != '' ) echo "value='$value'";
	}

}
