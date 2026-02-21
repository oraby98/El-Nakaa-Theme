<?php

function el_nakaa_register_acf_blocks()
{

	// Helper function to register blocks
	function register_el_nakaa_block($name, $title, $description = '', $icon = 'star-filled')
	{
		acf_register_block_type(array(
			'name'              => $name,
			'title'             => __($title, 'el-nakaa-theme'),
			'description'       => __($description, 'el-nakaa-theme'),
			'render_template'   => 'template-parts/blocks/' . $name . '.php',
			'category'          => 'formatting',
			'icon'              => $icon,
			'keywords'          => array(str_replace('qtc-', '', $name), 'qtc'),
			'mode'              => 'edit',
			'supports'          => array('align' => false, 'mode' => false),
		));
	}

	if (function_exists('acf_register_block_type')) {

		register_el_nakaa_block('el-nakaa-hero-section', 'El Nakaa Hero Section', 'Hero section for the homepage', 'cover-image');
		register_el_nakaa_block('el-nakaa-info-cards', 'El Nakaa Info Cards', 'Info cards section (Contact & Spare Parts)', 'columns');
		register_el_nakaa_block('el-nakaa-products', 'El Nakaa Products', 'Display products with tabs', 'cart');
		register_el_nakaa_block('el-nakaa-page-title', 'El Nakaa Page Title', 'Dynamic page title with background image', 'heading');
		register_el_nakaa_block('el-nakaa-about-us', 'El Nakaa About Us', 'About Us Section with Story, Vision, Mission, and Values', 'groups');
		register_el_nakaa_block('el-nakaa-whatsapp-card', 'El Nakaa WhatsApp Card', 'Floating WhatsApp Contact Card', 'whatsapp');
		register_el_nakaa_block('el-nakaa-contact-info', 'El Nakaa Contact Info', 'Contact Info Grid (Address, Phone, Hours)', 'location-alt');
		register_el_nakaa_block('el-nakaa-social-follow', 'El Nakaa Social Follow', 'Social Media Follow Links', 'share');
		register_el_nakaa_block('el-nakaa-map-faq', 'El Nakaa Map & FAQ', 'Google Map and FAQ Accordion', 'location');
	}
}

add_action('acf/init', 'el_nakaa_register_acf_blocks');

if (function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title'    => 'Footer Settings',
		'menu_slug'     => 'el-nakaa-footer-settings',
		'capability'    => 'edit_posts',
		'redirect'      => false
	));
}
