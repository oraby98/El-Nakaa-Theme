<?php
/**
 * Bathe functions
 *
 * @package Bathe
 */

/**
 * Set up theme defaults and registers support for various WordPress feaures.
 */
add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'bathe', get_theme_file_uri( 'languages' ) );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
			)
		);
		add_theme_support(
			'custom-background',
			apply_filters(
				'bathe_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 200,
				'width'       => 50,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'bathe' ),
				'mobile' => __( 'Mobile Menu', 'bathe' ),
                'footer_quick_links' => __( 'Footer Quick Links', 'bathe' ),
                'footer_categories' => __( 'Footer Categories', 'bathe' ),
                'footer_bottom_links' => __( 'Footer Bottom Links', 'bathe' ),
			)
		);
	}
);

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
add_action(
	'after_setup_theme',
	function () {
		$GLOBALS['content_width'] = apply_filters( 'bathe_content_width', 960 );
	},
	0
);

/**
 * Register widget area.
 */
add_action(
	'widgets_init',
	function () {
		register_sidebar(
			array(
				'name'          => __( 'Sidebar', 'bathe' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}
);

/**
 * Enqueue scripts and styles.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'bathe', get_theme_file_uri( 'assets/css/main.css' ), array(), rand() );
		wp_enqueue_style( 'font-awesome', get_theme_file_uri( 'assets/font awesome/all.min.css' ), array(), rand() );


		wp_enqueue_script( 'bathe', get_theme_file_uri( 'src/js/main.js' ), array(), rand(), true );
		wp_enqueue_script( 'font-awesome', get_theme_file_uri( 'assets/font awesome/all.min.js' ), array(), rand(), true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);


/**
 * Add Tailwind classes to menu links
 */
add_filter( 'nav_menu_link_attributes', function( $atts, $item, $args ) {
    if ( $args->theme_location == 'primary' ) {
        $atts['class'] = 'hover:text-mainColor transition-colors';
    } elseif ( $args->theme_location == 'mobile' ) {
        $atts['class'] = 'block hover:text-mainColor transition-colors';
    }
    return $atts;
}, 10, 3 );

/**
 * Add active class handling for Tailwind
 */
add_filter('nav_menu_css_class', function ($classes, $item, $args) {
    if ($args->theme_location == 'primary') {
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'text-mainColor'; // Active state color
        }
    }
    return $classes;
}, 10, 3);

// Acf block
// Acf block
require_once('acf-blocks.php');

/**
 * ACL Local JSON Configuration
 */
add_filter('acf/settings/save_json', function( $path ) {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function( $paths ) {
    unset($paths[0]); // Remove original path
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});


/**
 * Append Special Offers link to menus
 */
add_filter( 'wp_nav_menu_items', function( $items, $args ) {
    $special_offer_text = 'العروض الخاصة';
    $special_offer_link = '#'; // Update with actual link if available

    if ( $args->theme_location == 'primary' ) {
        $items .= '<li><a href="' . esc_url($special_offer_link) . '" class="text-red-600 hover:text-red-700 transition-colors">' . esc_html($special_offer_text) . '</a></li>';
    } elseif ( $args->theme_location == 'mobile' ) {
        $items .= '<li><a href="' . esc_url($special_offer_link) . '" class="block text-red-600 hover:text-red-700 transition-colors">' . esc_html($special_offer_text) . '</a></li>';
    }

    return $items;
}, 10, 2 );

/**
 * Remove Payment from Order Review (Separate Sections)
 */
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );


/**
 * Handle Clear Cart Logic
 */
add_action( 'init', function() {
    if ( isset( $_GET['empty_cart'] ) && $_GET['empty_cart'] == 'yes' ) {
        WC()->cart->empty_cart();
        wp_safe_redirect( wc_get_cart_url() );
        exit;
    }
} );

/**
 * Style YITH WooCommerce Wishlist Button
 */
add_filter( 'yith_wcwl_button_class', function( $classes ) {
    return 'flex-1 bg-white border border-gray-200 text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-mainColor hover:border-mainColor hover:text-secColor transition-all shadow-sm flex flex-row-reverse items-center justify-center gap-2 w-full';
} );

add_filter( 'yith_wcwl_button_icon', function( $icon ) {
    return '<i class="fa-regular fa-heart text-lg md:text-xl group-hover:text-red-500"></i>';
} );

// Translate YITH Strings safely via WordPress Gettext to prevent React crashes
add_filter( 'gettext', function( $translated_text, $text, $domain ) {
    if ( 'yith-woocommerce-wishlist' === $domain ) {
        switch ( $text ) {
            case 'Browse wishlist':
                return 'تصفح المفضلة';
            case 'Add to wishlist':
                return 'إضافة للمفضلة';
            case 'Product added!':
                return 'تمت الإضافة للمفضلة!';
        }
    }
    return $translated_text;
}, 20, 3 );

/**
 * Filter checkout fields to match custom template structure
 */
add_filter( 'woocommerce_checkout_fields', function( $fields ) {
    // Make sure we only require fields that exist in our custom design
    $fields['billing']['billing_last_name']['required'] = false;
    $fields['billing']['billing_email']['required'] = false;
    $fields['billing']['billing_postcode']['required'] = false;
    $fields['billing']['billing_state']['required'] = false;

    return $fields;
} );

/**
 * Provide a dummy email if empty to prevent WooCommerce email errors
 */
add_action('woocommerce_checkout_process', function() {
    if ( empty( $_POST['billing_email'] ) ) {
        $_POST['billing_email'] = 'guest_' . time() . '@no-email.com';
    }
});

/**
 * Update WooCommerce Header Cart Count via AJAX
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'bathe_header_add_to_cart_fragment' );
function bathe_header_add_to_cart_fragment( $fragments ) {
    ob_start();
    ?>
    <span class="cart-count absolute -top-2 -start-2 bg-secColor text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white"><?php echo function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
    <?php
    $fragments['span.cart-count'] = ob_get_clean();
    return $fragments;
}

/**
 * Update YITH Wishlist Header Count via AJAX Fragments
 */
add_filter( 'yith_wcwl_fragments', 'bathe_header_yith_wishlist_fragment' );
function bathe_header_yith_wishlist_fragment( $fragments ) {
    $count = function_exists( 'yith_wcwl_count_all_products' ) ? yith_wcwl_count_all_products() : 0;

    // We target the specific inner span class that our JS expects
    $fragments['.yith-wcwl-items-count'] = '<span class="yith-wcwl-items-count">' . $count . '</span>';

    return $fragments;
}

/**
 * Custom AJAX Endpoint to reliably fetch Wishlist Count
 */
add_action( 'wp_ajax_get_wishlist_count', 'bathe_get_wishlist_count' );
add_action( 'wp_ajax_nopriv_get_wishlist_count', 'bathe_get_wishlist_count' );
function bathe_get_wishlist_count() {
    $count = function_exists( 'yith_wcwl_count_all_products' ) ? yith_wcwl_count_all_products() : 0;
    wp_send_json_success( $count );
}
