<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



function ovp_ter_block_type($myBlockName, $ovp_BlockOption = array()) {
	register_block_type(
		'ovp-kit/' . $myBlockName,
		array_merge(
			array(
				'editor_script' => 'ovp-kit-editor-script',
				'editor_style' => 'ovp-kit-editor-style',
				'script' => 'ovp-kit-front-script',
				'style' => 'ovp-kit-front-style'
			),
			$ovp_BlockOption
		)
	);
}

function ovp_blocks_script() {
	wp_register_script(
		'ovp-kit-editor-script',
		plugins_url('dist/js/editor-script.js', __FILE__),
		array(
			'wp-blocks',
			'wp-i18n',
			'wp-element',
			'wp-editor',
			'wp-components',
			'wp-compose',
			'wp-data',
			'wp-autop',
		)
	);	
	ovp_ter_block_type('kahf-banner-k27f', array(
		'render_callback' => 'ovp_block_custom_post_fun',
		'attributes' => array(
			'postName' => array(	
				'type' => 'string',
				'source' => 'html',
			),
		)
	));
	
}
add_action('init', 'ovp_blocks_script');



function ovp_block_custom_post_fun ( $attributes, $content ) {
	
	return wpautop( $content );
}