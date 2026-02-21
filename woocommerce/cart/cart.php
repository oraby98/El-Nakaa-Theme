<?php

/**
 * Cart Page
 *
 * @package WooCommerce/Templates
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

<section class="container mx-auto py-10 px-4 min-h-[60vh]">
	<form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
		<div class="flex flex-col lg:flex-row gap-8">
			<!-- Cart Items List -->
			<div class="w-full lg:w-3/4">
				<!-- Items Container -->
				<div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
					<!-- Header -->
					<div class="flex items-center justify-between max-md:flex-col max-md:items-start max-md:gap-4 my-6 px-8">
						<h2 class="text-2xl font-bold text-secColor flex items-center gap-2">
							عربة التسوق
							<span class="text-gray-400 text-sm font-normal translation-y-1">
								(<?php echo WC()->cart->get_cart_contents_count(); ?> منتج)
							</span>
						</h2>
						<a href="<?php echo esc_url(wc_get_cart_url() . '?empty_cart=yes'); ?>"
							class="flex items-center gap-2 text-gray-400 hover:text-red-500 transition-colors group cursor-pointer"
							onclick="return confirm('هل أنت متأكد من مسح العربة؟');">
							<i class="fa-regular fa-trash-can"></i>
							<span class="text-sm">مسح العربة</span>
						</a>
					</div>

					<?php do_action('woocommerce_before_cart_table'); ?>

					<table class="w-full text-right border-collapse shop_table shop_table_responsive cart woocommerce-cart-form__contents">
						<thead class="hidden md:table-header-group text-gray-400 font-bold text-sm border-b border-gray-100">
							<tr>
								<th class="py-5 px-6 font-normal w-1/3"><?php esc_html_e('Product', 'woocommerce'); ?></th>
								<th class="py-5 px-6 font-normal text-center"><?php esc_html_e('Price', 'woocommerce'); ?></th>
								<th class="py-5 px-6 font-normal text-center"><?php esc_html_e('Quantity', 'woocommerce'); ?></th>
								<th class="py-5 px-6 font-normal text-center"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
								<th class="py-5 px-6 font-normal text-center w-10">&nbsp;</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100">
							<?php do_action('woocommerce_before_cart_contents'); ?>

							<?php
							foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
								$_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
								$product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

								if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
									$product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
							?>
									<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?> group hover:bg-gray-50 transition-colors grid grid-cols-1 md:table-row relative">

										<!-- Product -->
										<td class="p-4 md:p-6 md:table-cell product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
											<div class="flex items-center gap-4">
												<div class="w-20 h-20 bg-[#F9F9F9] rounded-xl flex items-center justify-center p-2 border border-gray-100 shrink-0">
													<?php
													$thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

													if (! $product_permalink) {
														echo $thumbnail; // PHPCS: XSS ok.
													} else {
														printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
													}
													?>
												</div>
												<div>
													<h3 class="font-bold text-secColor mb-1 leading-snug">
														<?php
														if (! $product_permalink) {
															echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
														} else {
															echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="text-secColor hover:text-mainColor transition-colors">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
														}

														do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

														// Meta data.
														echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

														// Backorder notification.
														if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
															echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
														}
														?>
													</h3>
												</div>
												<!-- Mobile Remove Button -->
												<?php
												echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
													'woocommerce_cart_item_remove_link',
													sprintf(
														'<a href="%s" class="md:hidden absolute top-4 left-4 text-gray-300 hover:text-red-500 remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa-solid fa-xmark"></i></a>',
														esc_url(wc_get_cart_remove_url($cart_item_key)),
														esc_html__('Remove this item', 'woocommerce'),
														esc_attr($product_id),
														esc_attr($_product->get_sku())
													),
													$cart_item_key
												);
												?>
											</div>
										</td>

										<!-- Price -->
										<td class="px-4 pb-2 md:p-6 md:table-cell align-middle product-price" data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
											<div class="flex justify-between md:justify-center w-full items-center">
												<span class="md:hidden text-gray-400 font-medium"><?php esc_html_e('Price:', 'woocommerce'); ?></span>
												<span class="font-bold text-secColor text-lg">
													<?php
													echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
													?>
												</span>
											</div>
										</td>

										<!-- Quantity -->
										<td class="px-4 py-2 md:p-6 md:table-cell align-middle product-quantity" data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
											<div class="flex justify-between md:justify-center w-full items-center">
												<span class="md:hidden text-gray-400 font-medium"><?php esc_html_e('Quantity:', 'woocommerce'); ?></span>
												<div class="quantity-wrapper flex items-center bg-[#FEF9E6] rounded-xl p-1.5 border border-yellow-200">
													<button type="button" class="w-8 h-8 rounded-lg bg-mainColor flex items-center justify-center text-secColor hover:bg-yellow-500 transition-colors increase-qty-btn cursor-pointer" onclick="this.parentNode.querySelector('input.qty').stepUp(); this.parentNode.querySelector('input.qty').dispatchEvent(new Event('change', {bubbles: true}));"><i class="fa-solid fa-plus text-xs"></i></button>

													<?php
													if ($_product->is_sold_individually()) {
														$min_quantity = 1;
														$max_quantity = 1;
													} else {
														$min_quantity = 0;
														$max_quantity = $_product->get_max_purchase_quantity();
													}
													?>
													<input
														type="number"
														name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]"
														value="<?php echo esc_attr($cart_item['quantity']); ?>"
														min="<?php echo esc_attr($min_quantity); ?>"
														<?php if ( $max_quantity > 0 ) : ?>
															max="<?php echo esc_attr($max_quantity); ?>"
														<?php endif; ?>
														step="1"
														class="w-10 text-center bg-transparent border-none font-bold text-secColor outline-none qty-input qty" />

													<button type="button" class="w-8 h-8 rounded-lg bg-yellow-100 flex items-center justify-center text-secColor hover:bg-yellow-200 transition-colors decrease-qty-btn cursor-pointer" onclick="this.parentNode.querySelector('input.qty').stepDown(); this.parentNode.querySelector('input.qty').dispatchEvent(new Event('change', {bubbles: true}));"><i class="fa-solid fa-minus text-xs"></i></button>
												</div>
											</div>
										</td>

										<!-- Subtotal -->
										<td class="p-4 md:p-6 md:table-cell align-middle product-subtotal" data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
											<div class="flex items-center justify-between md:justify-center gap-4 max-md:border-t max-md:pt-4 max-md:mt-2 w-full">
												<span class="md:hidden text-gray-400 font-medium"><?php esc_html_e('Subtotal:', 'woocommerce'); ?></span>
												<span class="font-bold text-secColor text-lg">
													<?php
													echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
													?>
												</span>
											</div>
										</td>

										<!-- Desktop Remove -->
										<td class="hidden md:table-cell align-middle text-center">
											<?php
											echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
												'woocommerce_cart_item_remove_link',
												sprintf(
													'<a href="%s" class="w-8 h-8 rounded-full text-gray-300 hover:text-red-500 transition-colors inline-flex justify-center items-center cursor-pointer remove-btn" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa-solid fa-xmark text-lg"></i></a>',
													esc_url(wc_get_cart_remove_url($cart_item_key)),
													esc_html__('Remove this item', 'woocommerce'),
													esc_attr($product_id),
													esc_attr($_product->get_sku())
												),
												$cart_item_key
											);
											?>
										</td>
									</tr>
							<?php
								}
							}
							?>

							<?php do_action('woocommerce_cart_contents'); ?>

							<tr>
								<td colspan="6" class="actions">
									<button type="submit" class="button hidden" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>" style="display:none !important;"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>
									<?php do_action('woocommerce_cart_actions'); ?>
									<?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
								</td>
							</tr>

							<?php do_action('woocommerce_after_cart_contents'); ?>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Summary Section -->
			<div class="w-full lg:w-1/4">
				<?php woocommerce_cart_totals(); ?>
			</div>
		</div>
	</form>
</section>

<?php do_action('woocommerce_after_cart'); ?>

<script>
	jQuery(function($) {
		// Auto-update cart on quantity change
		$(document).on('change', 'input.qty', function() {
			setTimeout(function() {
				$("[name='update_cart']").trigger("click");
			}, 100);
		});
	});
</script>
