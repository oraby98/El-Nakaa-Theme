<?php

/**
 * Block Name: El Nakaa Products
 *
 * This is the template that displays the products block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-products-' . $block['id'];
if (!empty($block['anchor'])) {
	$id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-products';
if (!empty($block['className'])) {
	$className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
	$className .= ' align' . $block['align'];
}


// Load values and assign defaults.
$section_title = get_field('section_title') ?: 'منتجات بيور المميزة';
$products_count = get_field('products_count') ?: 8;
$product_tabs = get_field('product_tabs');

// Prepare Query Arguments
$args = array(
	'post_type' => 'product',
	'posts_per_page' => $products_count,
	'post_status' => 'publish',
);

// If tabs have specific categories, filter the query to ensure we get relevant products
if ($product_tabs && is_array($product_tabs)) {
	$tab_category_ids = array();
	foreach ($product_tabs as $tab) {
		if (!empty($tab['tab_category'])) {
			$tab_category_ids[] = $tab['tab_category'];
		}
	}

	if (!empty($tab_category_ids)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'term_id',
				'terms'    => $tab_category_ids,
				'operator' => 'IN',
			),
		);
	}
}

$products_query = new WP_Query($args);
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> container mx-auto px-4 my-12 xl:my-24">
	<div class="flex flex-col md:flex-row justify-between items-center mb-8 xl:mb-10 gap-6">
		<!-- Title -->
		<h2 class="text-2xl xl:text-4xl font-bold text-secColor text-center md:text-start">
			<?php echo esc_html($section_title); ?>
		</h2>
		<!-- Tabs -->
		<div class="flex flex-wrap justify-center gap-2 md:gap-3" id="product-tabs">
			<?php
			// Always show "All" tab
			$activeClass = 'active bg-mainColor text-secColor shadow-sm';
			$inactiveClass = 'text-gray-500 hover:text-secColor hover:bg-gray-100';
			$baseClass = 'tab-btn px-4 md:px-6 xl:px-8 py-2 xl:py-2.5 rounded-lg font-bold transition-colors text-sm xl:text-base';
			?>
			<button class="<?php echo $baseClass . ' ' . $activeClass; ?>" data-tab="all">
				الكل
			</button>
			<?php if ($product_tabs && is_array($product_tabs)) : ?>
				<?php foreach ($product_tabs as $tab) :
					if (empty($tab['tab_category'])) continue;
					$target = 'cat-' . $tab['tab_category'];
				?>
					<button class="<?php echo $baseClass . ' ' . $inactiveClass; ?>" data-tab="<?php echo esc_attr($target); ?>">
						<?php echo esc_html($tab['tab_label']); ?>
					</button>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>

	<!-- Products Container -->
	<div class="space-y-8" id="products-container">
		<?php if ($products_query->have_posts()) : ?>
			<?php while ($products_query->have_posts()) : $products_query->the_post();
				global $product;
				if (!is_a($product, 'WC_Product')) {
					$product = wc_get_product(get_the_ID());
				}

				$price_html = $product->get_price_html();
				$rating = $product->get_average_rating();
				$rating_count = $product->get_rating_count();

				// Get Product Categories for Filtering
				$product_cats = get_the_terms(get_the_ID(), 'product_cat');
				$cat_classes = array();
				if ($product_cats && !is_wp_error($product_cats)) {
					foreach ($product_cats as $cat) {
						$cat_classes[] = 'cat-' . $cat->term_id;
					}
				}
				$cat_data = implode(' ', $cat_classes);
			?>
				<div class="product-item bg-gray-50 rounded-2xl h-auto xl:h-[400px] py-8 xl:py-0 px-4 xl:px-8 flex flex-col md:flex-row items-center gap-6 group hover:bg-gray-100 transition-all" data-categories="<?php echo esc_attr($cat_data); ?>">
					<!-- Image Content -->
					<div class="w-full md:w-1/3 h-64 md:h-72 xl:h-full flex justify-center overflow-hidden">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('large', array('class' => 'w-full h-full object-contain transition-transform group-hover:scale-110 group-hover:-translate-y-1.5 group-hover:translate-x-6 duration-700')); ?>
						<?php else : ?>
							<img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-contain transition-transform group-hover:scale-110 group-hover:-translate-y-1.5 group-hover:translate-x-6 duration-700" />
						<?php endif; ?>
					</div>

					<!-- Text Content -->
					<div class="w-full md:w-2/3 space-y-4 xl:space-y-6">
						<!-- Rating -->
						<div class="flex items-center gap-2 mb-2 justify-center md:justify-start">
							<div class="flex text-yellow-400 text-sm md:text-base">
								<?php for ($i = 0; $i < 5; $i++) : ?>
									<i class="fa-solid fa-star<?php echo ($i < $rating) ? '' : ' text-gray-300'; ?>"></i>
								<?php endfor; ?>
							</div>
							<span class="text-textColor text-base md:text-lg">
								<?php echo esc_html(number_format($rating, 1)); ?>
								<span class="text-gray-500 ms-2 text-sm md:text-base">(<?php echo esc_html($rating_count); ?> تقييم)</span>
							</span>
						</div>

						<h3 class="text-2xl md:text-3xl text-secColor mb-2 text-center md:text-start font-bold line-clamp-1">
							<a href="<?php the_permalink(); ?>" class="hover:text-mainColor transition-colors">
								<?php the_title(); ?>
							</a>
						</h3>
						<div class="text-gray-500 mb-6 text-base md:text-lg leading-relaxed text-center md:text-start line-clamp-2">
							<?php the_excerpt(); ?>
						</div>

						<div class="flex items-center justify-center md:justify-start gap-3">
						<?php if ($product->is_on_sale()) : ?>
							<span class="text-3xl md:text-4xl text-secColor font-bold"><?php echo number_format($product->get_price(), 0); ?> جنيه</span>
							<span class="text-gray-400 text-base md:text-lg line-through"><?php echo number_format($product->get_regular_price(), 0); ?> جنيه</span>
						<?php else : ?>
							<span class="text-3xl md:text-4xl text-secColor font-bold"><?php echo number_format($product->get_price(), 0); ?> جنيه</span>
						<?php endif; ?>
						</div>

						<div class="flex flex-col sm:flex-row gap-4 mt-6">
							<?php
							$in_cart = false;
							if ( function_exists( 'WC' ) && WC()->cart ) {
								foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
									if ( $cart_item['product_id'] == get_the_ID() ) {
										$in_cart = true;
										break;
									}
								}
							}

							if ( $in_cart ) {
								$btn_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
								$btn_text = 'عرض السلة';
								$btn_classes = 'flex-1 bg-mainColor text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group added';
							} else {
								$btn_url = $product->add_to_cart_url();
								$btn_text = $product->add_to_cart_text();
								$btn_classes = 'flex-1 bg-mainColor text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group ajax_add_to_cart add_to_cart_button';
							}
							?>
							<a href="<?php echo esc_url($btn_url); ?>"
								class="<?php echo esc_attr($btn_classes); ?>"
								data-product_id="<?php echo get_the_ID(); ?>"
								data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
								aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>"
								rel="nofollow">
								<i class="fa-solid fa-cart-shopping text-lg md:text-xl"></i>
								<span><?php echo esc_html($btn_text); ?></span>
							</a>
							<?php if ( defined( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) : ?>
								<div class="flex-1 min-w-[35%] el-nakaa-wishlist-wrapper" data-product-id="<?php echo get_the_ID(); ?>">
									<?php echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . get_the_ID() . '" label="إضافة للمفضلة" browse_wishlist_text="تصفح المفضلة" already_in_wishslist_text="تصفح المفضلة"]' ); ?>
								</div>
							<?php else : ?>
								<!-- Fallback static link if YITH plugin is missing/disabled -->
								<a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" class="flex-1 bg-white border border-gray-200 text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-mainColor hover:border-mainColor hover:text-secColor transition-all shadow-sm flex items-center justify-center gap-2 group cursor-pointer text-center w-full">
									<i class="fa-regular fa-heart text-lg md:text-xl group-hover:text-red-500"></i>
									<span>إضافة للمفضلة</span>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="text-center text-gray-500">لا توجد منتجات حالياً.</p>
		<?php endif; ?>
	</div>

	<div class="flex justify-center mt-12">
		<a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="bg-mainColor text-secColor px-16 py-3 rounded-xl font-bold text-lg hover:bg-mainColor hover:text-secColor transition-colors shadow-lg shadow-mainColor/20">
			مشاهده المزيد
		</a>
	</div>
</section>
