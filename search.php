<?php
/**
 * The template for displaying search results pages.
 */

get_header();

// Get the search query
$search_query = get_search_query();
?>

<div class="search-page-wrapper bg-white py-12 xl:py-24">
	<div class="container mx-auto px-4">
		<!-- Search Header -->
		<div class="text-center mb-12 xl:mb-16">
			<h1 class="text-3xl xl:text-5xl font-bold text-secColor mb-4">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'نتائج البحث عن: %s', 'el-nakaa-theme' ), '<span class="text-mainColor">"' . esc_html( $search_query ) . '"</span>' );
				?>
			</h1>
			<p class="text-gray-500 text-lg">
				<?php
				$results_count = $wp_query->found_posts;
				/* translators: %s: number of results. */
				printf( esc_html( _n( 'وجدنا نتيجة واحدة', 'وجدنا %s نتائج', $results_count, 'el-nakaa-theme' ) ), number_format_i18n( $results_count ) );
				?>
			</p>
		</div>

		<?php if ( have_posts() ) : ?>
			<!-- Products Container (Using product block layout) -->
			<div class="space-y-8 max-w-6xl mx-auto">
				<?php
				while ( have_posts() ) :
					the_post();

					// If it's a WooCommerce product, display it using the card format
					if ( get_post_type() === 'product' && class_exists( 'WooCommerce' ) ) :
						global $product;
						if ( ! is_a( $product, 'WC_Product' ) ) {
							$product = wc_get_product( get_the_ID() );
						}

						$rating       = $product->get_average_rating();
						$rating_count = $product->get_rating_count();
						?>
						<div class="product-item bg-gray-50 rounded-2xl h-auto xl:h-[400px] py-8 xl:py-0 px-4 xl:px-8 flex flex-col md:flex-row items-center gap-6 group hover:bg-gray-100 transition-all border border-transparent hover:border-gray-200">
							<!-- Image Content -->
							<div class="w-full md:w-1/3 h-64 md:h-72 xl:h-full flex justify-center overflow-hidden">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-contain transition-transform group-hover:scale-110 group-hover:-translate-y-1.5 group-hover:translate-x-6 duration-700' ) ); ?>
								<?php else : ?>
									<img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-contain transition-transform group-hover:scale-110 group-hover:-translate-y-1.5 group-hover:translate-x-6 duration-700" />
								<?php endif; ?>
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
									<a href="<?php the_permalink(); ?>" class="hover:text-mainColor transition-colors">
										<?php the_title(); ?>
									</a>
								</h3>
								<div class="text-gray-500 mb-6 text-base md:text-lg leading-relaxed text-center md:text-start line-clamp-2">
									<?php the_excerpt(); ?>
								</div>

								<div class="flex items-center justify-center md:justify-start gap-3">
								<?php if ( $product->is_on_sale() ) : ?>
									<span class="text-3xl md:text-4xl text-secColor font-bold"><?php echo number_format( $product->get_price(), 0 ); ?> جنيه</span>
									<span class="text-gray-400 text-base md:text-lg line-through"><?php echo number_format( $product->get_regular_price(), 0 ); ?> جنيه</span>
								<?php else : ?>
									<span class="text-3xl md:text-4xl text-secColor font-bold"><?php echo number_format( $product->get_price(), 0 ); ?> جنيه</span>
								<?php endif; ?>
								</div>

								<div class="flex flex-col sm:flex-row gap-4 mt-6">
									<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
										class="flex-1 bg-mainColor text-secColor py-3 md:py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] hover:text-secColor transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group ajax_add_to_cart add_to_cart_button"
										data-product_id="<?php echo get_the_ID(); ?>"
										data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
										aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
										rel="nofollow">
										<i class="fa-solid fa-cart-shopping text-lg md:text-xl"></i>
										<span><?php echo esc_html( $product->add_to_cart_text() ); ?></span>
									</a>
									<?php if ( defined( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) : ?>
										<div class="flex-1 min-w-[35%] el-nakaa-wishlist-wrapper">
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
						<?php
					else :
						// General Post Display for non-product search results
						?>
						<div class="bg-gray-50 rounded-2xl p-6 xl:p-8 hover:bg-gray-100 transition-all border border-transparent hover:border-gray-200">
							<h3 class="text-2xl font-bold text-secColor mb-3">
								<a href="<?php the_permalink(); ?>" class="hover:text-mainColor transition-colors">
									<?php the_title(); ?>
								</a>
							</h3>
							<?php if ( 'post' === get_post_type() ) : ?>
								<div class="text-sm text-gray-400 mb-4 flex items-center gap-4">
									<span><i class="fa-regular fa-calendar ml-1"></i> <?php echo get_the_date(); ?></span>
								</div>
							<?php endif; ?>
							<div class="text-gray-500 leading-relaxed line-clamp-3 mb-4">
								<?php the_excerpt(); ?>
							</div>
							<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-mainColor font-bold hover:text-secColor transition-colors gap-2">
								<span>اقرأ المزيد</span>
								<i class="fa-solid fa-arrow-left text-sm"></i>
							</a>
						</div>
						<?php
					endif;
				endwhile;
				?>
			</div>

			<!-- Pagination -->
			<div class="mt-16 flex justify-center">
				<?php
				echo paginate_links(
					array(
						'prev_text' => '<span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-secColor hover:bg-mainColor transition-colors"><i class="fa-solid fa-chevron-right"></i></span>',
						'next_text' => '<span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-secColor hover:bg-mainColor transition-colors"><i class="fa-solid fa-chevron-left"></i></span>',
						'type'      => 'list',
						'class'     => 'flex gap-2 items-center el-nakaa-pagination',
					)
				);
				?>
			</div>
			<style>
				ul.page-numbers {
					display: flex;
					gap: 0.5rem;
					align-items: center;
				}
				ul.page-numbers li a.page-numbers,
				ul.page-numbers li span.page-numbers {
					display: flex;
					align-items: center;
					justify-content: center;
					width: 2.5rem;
					height: 2.5rem;
					border-radius: 9999px;
					background-color: #f3f4f6;
					color: #1a1a1a;
					font-weight: bold;
					transition: all 0.3s ease;
				}
				ul.page-numbers li a.page-numbers:hover {
					background-color: #ffd100;
				}
				ul.page-numbers li span.page-numbers.current {
					background-color: #ffd100;
					color: #1a1a1a;
				}
			</style>

		<?php else : ?>
			<!-- Empty State -->
			<div class="text-center py-16 xl:py-24 max-w-2xl mx-auto">
				<div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
					<i class="fa-solid fa-magnifying-glass text-4xl"></i>
				</div>
				<h2 class="text-2xl xl:text-3xl font-bold text-secColor mb-4">
					للأسف، لم نتمكن من العثور على أي نتائج
				</h2>
				<p class="text-gray-500 mb-8 text-lg">
					يرجى التأكد من كتابة الكلمات بشكل صحيح أو المحاولة باستخدام كلمات بحث مختلفة.
				</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-mainColor text-secColor px-8 py-3.5 rounded-xl font-bold hover:bg-[#ffe14d] transition-colors shadow-lg shadow-mainColor/20">
					العودة للصفحة الرئيسية
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
