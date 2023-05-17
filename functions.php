<?php 

	function insopt( $option = '', $default = null ) {
		$options = get_option( 'wiopt' ); 
		return ( isset( $options[$option] ) ) ? $options[$option] : $default;
	}

	


?>