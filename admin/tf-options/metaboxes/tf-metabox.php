<?php
// don't load directly
defined( 'ABSPATH' ) || exit; 
TF_Metabox::metabox( 'tf_post_opt', array(
	'title'     => 'post meta Settings',
	'post_type' => 'tf_post-meta',
	'sections'  => array(
		
	),
) );
