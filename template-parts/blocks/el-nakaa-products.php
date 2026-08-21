<?php

/**
 * Block Name: El Nakaa Products
 *
 * This is the template that displays the products block.
 */

$attributes = isset($attributes) && is_array($attributes) ? $attributes : array();

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
$section_title = el_nakaa_block_value($attributes, 'section_title') ?: 'منتجات بيور المميزة';
$products_count = el_nakaa_block_value($attributes, 'products_count', 8);
$products_count = !empty($products_count) ? (int)$products_count : 8;
$template_style = el_nakaa_block_value($attributes, 'template') ?: '1';
$product_tabs = el_nakaa_block_value($attributes, 'product_tabs');
$show_view_more = el_nakaa_block_value($attributes, 'show_view_more', 1);
$view_more_text = el_nakaa_block_value($attributes, 'view_more_text') ?: 'مشاهده المزيد';
$view_more_url = el_nakaa_block_value($attributes, 'view_more_url');

// Prepare Query Arguments
$args = array(
	'post_type'      => 'product',
	'posts_per_page' => $products_count,
	'post_status'    => 'publish',
);

// If tabs have specific categories, filter the query to ensure we get relevant products
if ($product_tabs && is_array($product_tabs)) {
	$tab_category_ids = array();
	foreach ($product_tabs as $tab) {
		if (!empty($tab['tab_category'])) {
			$tab_category_ids[] = (int)$tab['tab_category'];
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
			<?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
				<?php include get_theme_file_path('template-parts/product-card.php'); ?>
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p class="text-center text-gray-500">لا توجد منتجات حالياً.</p>
		<?php endif; ?>
	</div>

	<?php
	$max_pages = (int) $products_query->max_num_pages;
	$cat_ids_str = !empty($tab_category_ids) ? implode(',', $tab_category_ids) : '';
	?>
	<?php if (!empty($show_view_more) && $max_pages > 1) : ?>
		<div class="flex justify-center mt-12 load-more-wrapper">
			<button type="button"
				class="load-more-products-btn bg-mainColor text-secColor px-16 py-3 rounded-xl font-bold text-lg hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-3 cursor-pointer"
				data-page="1"
				data-max-pages="<?php echo esc_attr($max_pages); ?>"
				data-per-page="<?php echo esc_attr($products_count); ?>"
				data-template="<?php echo esc_attr($template_style); ?>"
				data-categories="<?php echo esc_attr($cat_ids_str); ?>"
				data-btn-text="<?php echo esc_attr($view_more_text); ?>">
				<span class="btn-text"><?php echo esc_html($view_more_text); ?></span>
				<i class="btn-spinner fa-solid fa-spinner fa-spin hidden text-xl"></i>
			</button>
		</div>
	<?php endif; ?>
</section>
