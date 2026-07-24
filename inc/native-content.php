<?php
/**
 * Native storage for theme-wide content formerly managed by ACF.
 *
 * @package Bathe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return footer settings from native storage, falling back to ACF before cutover.
 *
 * @return array
 */
function el_nakaa_footer_settings() {
	$settings = get_option( 'el_nakaa_footer_settings', array() );
	if ( is_array( $settings ) && $settings ) {
		return $settings;
	}

	$settings = array();
	$fields   = array(
		'footer_features',
		'footer_logo',
		'footer_about_text',
		'footer_socials',
		'footer_address',
		'footer_phone',
		'footer_email',
		'footer_copyright',
	);

	if ( function_exists( 'get_field' ) ) {
		foreach ( $fields as $field ) {
			$settings[ $field ] = get_field( $field, 'option' );
		}
	}

	return $settings;
}

/**
 * Read one footer value.
 *
 * @param string $key     Setting key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function el_nakaa_footer_value( $key, $default = '' ) {
	$settings = el_nakaa_footer_settings();
	return isset( $settings[ $key ] ) && '' !== $settings[ $key ] ? $settings[ $key ] : $default;
}

/**
 * Normalize an ACF/native image into a URL.
 *
 * @param mixed $image Image array, ID, or URL.
 * @return string
 */
function el_nakaa_image_url( $image ) {
	if ( is_array( $image ) ) {
		if ( ! empty( $image['url'] ) ) {
			return $image['url'];
		}
		if ( ! empty( $image['id'] ) ) {
			return wp_get_attachment_image_url( absint( $image['id'] ), 'full' ) ?: '';
		}
		if ( ! empty( $image['ID'] ) ) {
			return wp_get_attachment_image_url( absint( $image['ID'] ), 'full' ) ?: '';
		}
	}
	if ( is_numeric( $image ) ) {
		return wp_get_attachment_image_url( absint( $image ), 'full' ) ?: '';
	}
	return is_string( $image ) ? $image : '';
}

/**
 * Product features from native post meta, with an ACF fallback before cutover.
 *
 * @param int $post_id Product ID.
 * @return array
 */
function el_nakaa_product_features( $post_id ) {
	$features = get_post_meta( $post_id, '_el_nakaa_product_features', true );
	if ( is_array( $features ) ) {
		return $features;
	}

	if ( function_exists( 'get_field' ) ) {
		$features = get_field( 'product_features', $post_id );
		return is_array( $features ) ? $features : array();
	}

	return array();
}

