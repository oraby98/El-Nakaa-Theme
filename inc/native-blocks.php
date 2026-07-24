<?php
/**
 * Native dynamic blocks and ACF compatibility helpers.
 *
 * @package Bathe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Blocks being migrated from ACF to WordPress core blocks.
 *
 * The old ACF blocks deliberately keep their existing names while ACF is active.
 * Native blocks use a new namespace so both implementations can coexist during
 * the production migration window.
 *
 * @return array<string,array<string,string>>
 */
function el_nakaa_native_block_definitions() {
	return array(
		'el-nakaa-hero-section'  => array( 'title' => 'El Nakaa Hero Section', 'icon' => 'cover-image' ),
		'el-nakaa-info-cards'    => array( 'title' => 'El Nakaa Info Cards', 'icon' => 'columns' ),
		'el-nakaa-products'      => array( 'title' => 'El Nakaa Products', 'icon' => 'cart' ),
		'el-nakaa-page-title'    => array( 'title' => 'El Nakaa Page Title', 'icon' => 'heading' ),
		'el-nakaa-about-us'      => array( 'title' => 'El Nakaa About Us', 'icon' => 'groups' ),
		'el-nakaa-whatsapp-card' => array( 'title' => 'El Nakaa WhatsApp Card', 'icon' => 'whatsapp' ),
		'el-nakaa-contact-info'  => array( 'title' => 'El Nakaa Contact Info', 'icon' => 'location-alt' ),
		'el-nakaa-social-follow' => array( 'title' => 'El Nakaa Social Follow', 'icon' => 'share' ),
		'el-nakaa-map-faq'       => array( 'title' => 'El Nakaa Map & FAQ', 'icon' => 'location' ),
	);
}

/**
 * Read the field schema already committed with the theme.
 *
 * The JSON is treated as migration metadata only; it does not require ACF.
 *
 * @param string $slug Block slug without namespace.
 * @return array
 */
function el_nakaa_native_block_fields( $slug ) {
	$file = get_theme_file_path( 'acf-json/group_' . str_replace( '-', '_', $slug ) . '.json' );

	if ( ! is_readable( $file ) ) {
		return array();
	}

	$group = json_decode( file_get_contents( $file ), true );

	return is_array( $group ) && isset( $group['fields'] ) && is_array( $group['fields'] )
		? $group['fields']
		: array();
}

/**
 * Convert ACF field metadata to a core block attribute definition.
 *
 * @param array $field ACF field metadata.
 * @return array
 */
function el_nakaa_native_attribute_from_field( $field ) {
	$type = isset( $field['type'] ) ? $field['type'] : 'text';

	if ( 'repeater' === $type ) {
		return array( 'type' => 'array', 'default' => array() );
	}

	if ( 'image' === $type ) {
		return array( 'type' => 'object' );
	}

	if ( 'number' === $type || 'taxonomy' === $type ) {
		$attribute = array( 'type' => 'integer' );
		if ( isset( $field['default_value'] ) && '' !== $field['default_value'] ) {
			$attribute['default'] = (int) $field['default_value'];
		}
		return $attribute;
	}

	$attribute = array( 'type' => 'string' );
	if ( isset( $field['default_value'] ) && '' !== $field['default_value'] ) {
		$attribute['default'] = (string) $field['default_value'];
	}

	return $attribute;
}

/**
 * Build block attributes for server and editor registration.
 *
 * @param array $fields ACF-compatible field metadata.
 * @return array
 */
function el_nakaa_native_attributes_from_fields( $fields ) {
	$attributes = array();

	foreach ( $fields as $field ) {
		if ( empty( $field['name'] ) || 'tab' === $field['type'] ) {
			continue;
		}

		$attributes[ $field['name'] ] = el_nakaa_native_attribute_from_field( $field );
	}

	return $attributes;
}

/**
 * Get a value from native attributes and fall back to ACF during migration.
 *
 * @param array  $attributes Native block attributes.
 * @param string $name       Field name.
 * @param mixed  $default    Fallback value.
 * @return mixed
 */
function el_nakaa_block_value( $attributes, $name, $default = null ) {
	if ( is_array( $attributes ) && array_key_exists( $name, $attributes ) ) {
		$value = $attributes[ $name ];
		if ( null !== $value && '' !== $value && array() !== $value ) {
			return $value;
		}
	}

	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name );
		if ( null !== $value && false !== $value && '' !== $value ) {
			return $value;
		}
	}

	return $default;
}

/**
 * Register the common editor and all native dynamic blocks.
 */
function el_nakaa_register_native_blocks() {
	$definitions = el_nakaa_native_block_definitions();
	$editor_data = array();

	wp_register_style(
		'el-nakaa-native-blocks-font-awesome',
		get_theme_file_uri( 'assets/font awesome/all.min.css' ),
		array(),
		filemtime( get_theme_file_path( 'assets/font awesome/all.min.css' ) )
	);

	wp_register_style(
		'el-nakaa-native-blocks-editor',
		get_theme_file_uri( 'assets/css/main.css' ),
		array( 'el-nakaa-native-blocks-font-awesome' ),
		filemtime( get_theme_file_path( 'assets/css/main.css' ) )
	);

	wp_register_script(
		'el-nakaa-native-blocks-editor',
		get_theme_file_uri( 'assets/js/native-blocks.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-data', 'wp-element', 'wp-i18n', 'wp-server-side-render', 'wp-api-fetch' ),
		filemtime( get_theme_file_path( 'assets/js/native-blocks.js' ) ),
		true
	);

	foreach ( $definitions as $slug => $definition ) {
		$fields     = el_nakaa_native_block_fields( $slug );
		$attributes = el_nakaa_native_attributes_from_fields( $fields );
		$name       = 'el-nakaa/' . substr( $slug, strlen( 'el-nakaa-' ) );
		$template   = get_theme_file_path( 'template-parts/blocks/' . $slug . '.php' );

		register_block_type(
			$name,
			array(
				'api_version'     => 3,
				'title'           => $definition['title'],
				'category'        => 'formatting',
				'icon'            => $definition['icon'],
				'attributes'      => $attributes,
				'editor_script'   => 'el-nakaa-native-blocks-editor',
				'editor_style'    => 'el-nakaa-native-blocks-editor',
				'supports'        => array(
					'align'  => false,
					'anchor' => true,
					'html'   => false,
				),
				'render_callback' => static function ( $attributes, $content, $parsed_block = null ) use ( $template, $slug ) {
					if ( ! is_readable( $template ) ) {
						return '';
					}

					$block = array(
						'id'        => wp_unique_id( $slug . '-' ),
						'anchor'    => isset( $attributes['anchor'] ) ? $attributes['anchor'] : '',
						'className' => isset( $attributes['className'] ) ? $attributes['className'] : '',
						'align'     => isset( $attributes['align'] ) ? $attributes['align'] : '',
					);

					ob_start();
					include $template;
					return ob_get_clean();
				},
			)
		);

		$editor_data[] = array(
			'name'       => $name,
			'title'      => $definition['title'],
			'icon'       => $definition['icon'],
			'fields'     => $fields,
			'attributes' => $attributes,
		);
	}

	wp_add_inline_script(
		'el-nakaa-native-blocks-editor',
		'window.elNakaaNativeBlocks = ' . wp_json_encode( $editor_data ) . ';',
		'before'
	);
}
add_action( 'init', 'el_nakaa_register_native_blocks' );
