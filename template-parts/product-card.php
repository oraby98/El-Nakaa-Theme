<?php
/**
 * Template part for displaying a single product card in product blocks and AJAX loops.
 *
 * @package Bathe
 */

global $product;
if ( ! is_a( $product, 'WC_Product' ) ) {
	$product = wc_get_product( get_the_ID() );
}
if ( ! $product ) {
	return;
}

$product_id     = $product->get_id();
$template_style = isset( $template_style ) ? (string) $template_style : '1';
$price_html     = $product->get_price_html();
$rating         = (float) $product->get_average_rating();
$rating_count   = (int) $product->get_rating_count();

// Get Product Categories for Filtering
$product_cats = get_the_terms( $product_id, 'product_cat' );
$cat_classes  = array();
if ( $product_cats && ! is_wp_error( $product_cats ) ) {
	foreach ( $product_cats as $cat ) {
		$cat_classes[] = 'cat-' . $cat->term_id;
	}
}
$cat_data = implode( ' ', $cat_classes );
?>
<div class="product-item bg-gray-50 rounded-2xl h-auto xl:h-[400px] py-8 xl:py-0 px-4 xl:px-8 flex flex-col md:flex-row items-center gap-6 group hover:bg-gray-100 transition-all" data-categories="<?php echo esc_attr( $cat_data ); ?>">
	<!-- Image Content -->
	<div class="w-full md:w-1/3 h-64 md:h-72 xl:h-full flex justify-center overflow-hidden">
		<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="w-full h-full flex justify-center items-center">
			<?php if ( has_post_thumbnail( $product_id ) ) : ?>
				<?php echo get_the_post_thumbnail( $product_id, 'large', array( 'class' => 'w-full h-full object-contain transition-transform group-hover:scale-110 group-hover:-translate-y-1.5 group-hover:translate-x-6 duration-700' ) ); ?>
			<?php else : ?>
				<img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" class="w-full h-full object-contain transition-transform group-hover:scale-110 group-hover:-translate-y-1.5 group-hover:translate-x-6 duration-700" />
			<?php endif; ?>
		</a>
	</div>
	<!-- Text Content -->
	<div class="w-full md:w-2/3 space-y-4 xl:space-y-6">
		<!-- Rating -->
		<div class="flex items-center gap-2 mb-2 justify-center md:justify-start">
			<div class="flex text-yellow-400 text-sm md:text-base">
				<?php for ( $i = 0; $i < 5; $i++ ) : ?>
					<i class="fa-solid fa-star<?php echo ( $i < $rating ) ? '' : ' text-gray-300'; ?>"></i>
				<?php endfor; ?>
			</div>
			<span class="text-textColor text-base md:text-lg">
				<?php echo esc_html( number_format( $rating, 1 ) ); ?>
				<span class="text-gray-500 ms-2 text-sm md:text-base">(<?php echo esc_html( $rating_count ); ?> تقييم)</span>
			</span>
		</div>

		<h3 class="text-2xl md:text-3xl text-secColor mb-2 text-center md:text-start font-bold line-clamp-1">
			<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>" class="hover:text-mainColor transition-colors">
				<?php echo esc_html( $product->get_name() ); ?>
			</a>
		</h3>
		<div class="text-gray-500 mb-6 text-base md:text-lg leading-relaxed text-center md:text-start line-clamp-2">
			<?php echo wp_kses_post( $product->get_short_description() ?: get_the_excerpt( $product_id ) ); ?>
		</div>

		<?php if ( '1' === $template_style ) : ?>
			<div class="flex items-center justify-center md:justify-start gap-3">
				<?php if ( $product->is_on_sale() ) : ?>
					<span class="text-3xl md:text-4xl text-secColor font-bold"><?php echo number_format( (float) $product->get_price(), 0 ); ?> جنيه</span>
					<span class="text-gray-400 text-base md:text-lg line-through"><?php echo number_format( (float) $product->get_regular_price(), 0 ); ?> جنيه</span>
				<?php else : ?>
					<span class="text-3xl md:text-4xl text-secColor font-bold"><?php echo number_format( (float) $product->get_price(), 0 ); ?> جنيه</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="flex flex-col sm:flex-row gap-4 mt-6">
			<?php
			if ( '2' === $template_style ) {
				$btn_url     = get_permalink( $product_id );
				$btn_text    = 'تصفح المنتج';
				$btn_classes = 'w-full bg-mainColor text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group';
			} else {
				$in_cart = false;
				if ( function_exists( 'WC' ) && WC()->cart ) {
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						if ( $cart_item['product_id'] == $product_id ) {
							$in_cart = true;
							break;
						}
					}
				}

				if ( $in_cart ) {
					$btn_url     = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
					$btn_text    = 'عرض السلة';
					$btn_classes = 'flex-1 bg-mainColor text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group added';
				} else {
					$btn_url     = $product->add_to_cart_url();
					$btn_text    = $product->add_to_cart_text();
					$btn_classes = 'flex-1 bg-mainColor text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group ajax_add_to_cart add_to_cart_button';
				}
			}
			?>
			<a href="<?php echo esc_url( $btn_url ); ?>"
				class="<?php echo esc_attr( $btn_classes ); ?>"
				<?php if ( '2' !== $template_style ) : ?>
				data-product_id="<?php echo esc_attr( $product_id ); ?>"
				data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
				aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
				rel="nofollow"
				<?php endif; ?>>
				<?php if ( '2' === $template_style ) : ?>
					<i class="fa-solid fa-eye text-lg md:text-xl"></i>
				<?php else : ?>
					<i class="fa-solid fa-cart-shopping text-lg md:text-xl"></i>
				<?php endif; ?>
				<span><?php echo esc_html( $btn_text ); ?></span>
			</a>
			<?php if ( '2' !== $template_style ) : ?>
				<?php
				$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );
				$buy_now_url  = add_query_arg( 'add-to-cart', $product_id, $checkout_url );
				?>
				<a href="<?php echo esc_url( $buy_now_url ); ?>" class="flex-1 bg-white border border-gray-200 text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-mainColor hover:border-mainColor hover:text-secColor transition-all shadow-sm flex items-center justify-center gap-2 group cursor-pointer text-center w-full min-w-[35%]">
					<i class="fa-solid fa-bolt text-lg md:text-xl text-yellow-500 group-hover:text-secColor"></i>
					<span>شراء الآن</span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
