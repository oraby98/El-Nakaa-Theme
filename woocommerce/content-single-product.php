<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$attachment_ids = $product->get_gallery_image_ids();
$main_image_id = $product->get_image_id();
$main_image_url = wp_get_attachment_url( $main_image_id );
$rating_count = $product->get_rating_count();
$average_rating = $product->get_average_rating();

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

    <!-- Product Details Section -->
    <section class="container mx-auto px-4 my-8 lg:my-12">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-16 items-start">

        <!-- Image Gallery (Right Column) -->
        <div class="space-y-4">
          <!-- Main Image -->
          <div class="bg-gray-50 rounded-2xl p-6 xl:p-8 relative aspect-square flex items-center justify-center overflow-hidden border border-gray-100 group">
            <?php if($product->is_featured()): ?>
            <span class="absolute top-4 start-4 bg-yellow-400/10 text-yellow-600 px-3 py-1 rounded-full text-sm font-bold border border-yellow-200 z-10">
              الأكثر طلباً و تميزاً
            </span>
            <?php endif; ?>

            <img id="main-product-image" src="<?php echo esc_url($main_image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="w-full h-full object-contain mix-blend-multiply hover:scale-105 transition-transform duration-500" />
          </div>

          <!-- Thumbnails -->
          <?php if($attachment_ids): ?>
          <div class="grid grid-cols-3 gap-4">
            <!-- Main Image Thumbnail -->
            <div class="product-thumb bg-gray-50 rounded-xl p-2 cursor-pointer border-2 border-mainColor transition-all aspect-square flex items-center justify-center" onclick="changeMainImage('<?php echo esc_url($main_image_url); ?>', this)">
              <img src="<?php echo esc_url($main_image_url); ?>" alt="Main Thumbnail" class="w-full h-full object-contain mix-blend-multiply" />
            </div>

            <?php foreach($attachment_ids as $attachment_id):
                $image_url = wp_get_attachment_url($attachment_id);
            ?>
            <div class="product-thumb bg-gray-50 rounded-xl p-2 cursor-pointer border-2 border-transparent hover:border-mainColor transition-all aspect-square flex items-center justify-center" onclick="changeMainImage('<?php echo esc_url($image_url); ?>', this)">
              <img src="<?php echo esc_url($image_url); ?>" alt="Thumbnail" class="w-full h-full object-contain mix-blend-multiply" />
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <!-- Details (Left Column) -->
        <div class="space-y-6 lg:py-4">
          <!-- Title -->
          <h1 class="text-2xl lg:text-3xl xl:text-4xl font-bold text-secColor leading-tight">
            <?php the_title(); ?>
          </h1>

          <div class="flex flex-wrap items-center justify-between gap-4 w-full">
            <!-- Rating -->
            <?php if($rating_count > 0): ?>
            <div class="flex items-center gap-2">
              <span class="text-gray-400 text-sm">(<?php echo $rating_count; ?> تقييم)</span>
              <span class="text-textColor font-bold text-lg"><?php echo $average_rating; ?></span>
              <div class="flex text-yellow-500 text-sm gap-0.5">
                <?php for($i=1; $i<=5; $i++): ?>
                    <i class="fa-solid fa-star <?php echo $i <= $average_rating ? '' : 'text-gray-200'; ?>"></i>
                <?php endfor; ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- Price -->
            <div class="flex items-center gap-4">
                <?php if($product->is_on_sale()): ?>
                    <span class="text-3xl lg:text-4xl font-bold text-textColor">
                        <?php echo wc_price($product->get_sale_price()); ?>
                    </span>
                    <span class="text-lg lg:text-xl text-gray-400 line-through font-medium">
                        <?php echo wc_price($product->get_regular_price()); ?>
                    </span>
                <?php else: ?>
                    <span class="text-3xl lg:text-4xl font-bold text-textColor">
                        <?php echo wc_price($product->get_price()); ?>
                    </span>
                <?php endif; ?>
            </div>
          </div>

          <!-- Short Desc -->
          <div class="text-secColor text-base lg:text-lg leading-relaxed">
            <?php echo apply_filters( 'woocommerce_short_description', $product->get_description() ); ?>
          </div>

          <!-- Features (Static for now, dynamic later if needed) -->
          <!--
          <ul class="space-y-3 list-disc list-inside text-secColor">
            <li class=" "><span>تدفق هواء قوي ومتوازن</span></li>
          </ul>
          -->

          <!-- Long Description Area (Optional or keep simple) -->
          <!-- <div class="bg-gray-50 p-4 lg:p-6 rounded-xl border border-gray-100 text-sm lg:text-base text-gray-600 leading-relaxed text-justify">
             <?php // the_content(); ?>
          </div> -->

          <!-- Add to Cart Form -->
          <?php
          if( $product->is_type('variable') ) {
              do_action( 'woocommerce_variable_add_to_cart' );
          }
          ?>

          <?php if( $product->is_type('simple') ) : ?>
          <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

             <!-- Quantity Selector -->
             <div class="flex items-center justify-between gap-4 pt-2 pb-4">
                <span class="text-xl font-bold text-secColor">الكمية :</span>
                <div class="flex items-center bg-yellow-50 rounded-2xl p-2 border border-yellow-200">
                  <button id="increase-qty" type="button" class="w-8 h-8 py-1 px-6 rounded-xl bg-mainColor flex items-center justify-center text-secColor hover:bg-yellow-500 transition-colors">
                    <i class="fa-solid fa-plus text-sm"></i>
                  </button>

                  <span id="qty-val" class="w-12 text-center font-bold text-lg text-secColor select-none">1</span>

                  <!-- Hidden Input for WooCommerce Form Submission -->
                  <input type="hidden" name="quantity" id="real-qty" value="1" min="<?php echo $product->get_min_purchase_quantity(); ?>" max="<?php echo $product->get_max_purchase_quantity(); ?>">

                  <button id="decrease-qty" type="button" class="w-8 h-8 py-1 px-6 rounded-xl bg-yellow-100 flex items-center justify-center text-secColor hover:bg-yellow-300 border-2 border-yellow-200 transition-colors">
                    <i class="fa-solid fa-minus text-sm"></i>
                  </button>
                </div>
              </div>

              <script>
                  // Sync hidden input with span changes (MutationObserver since main.js updates innerText)
                  document.addEventListener("DOMContentLoaded", () => {
                      const qtyVal = document.getElementById('qty-val');
                      const realQty = document.getElementById('real-qty');

                      if(qtyVal && realQty) {
                          // Observer for changes in qty-val span
                          const observer = new MutationObserver((mutations) => {
                              mutations.forEach((mutation) => {
                                  if (mutation.type === 'childList' || mutation.type === 'characterData') {
                                      realQty.value = qtyVal.innerText;
                                  }
                              });
                          });
                          observer.observe(qtyVal, { childList: true, characterData: true, subtree: true });

                          // Initial sync
                          realQty.value = qtyVal.innerText;
                      }
                  });
              </script>

              <!-- Actions -->
              <div class="flex flex-col lg:flex-row gap-4 pt-4">
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="flex-1 !bg-secColor text-white py-3.5 rounded-xl font-bold text-lg hover:bg-gray-800 transition-all shadow-lg shadow-secColor/20 flex items-center justify-center gap-2 group single_add_to_cart_button button alt">
                  <i class="fa-solid fa-cart-shopping"></i>
                  <span>إضافة للسلة</span>
                </button>

                <!-- Wishlist (Placeholder) -->
                <button type="button" class="flex-1 bg-white border border-gray-200 text-secColor py-3.5 rounded-xl font-bold hover:border-red-500 hover:text-red-500 transition-all flex items-center justify-center gap-2 group">
                  <i class="fa-regular fa-heart text-xl leading-none pt-1"></i>
                  <span>إضافة للمفضلة</span>
                </button>
              </div>
          </form>
          <?php endif; ?>

        </div>
      </div>
    </section>

    <!-- Features Breakdown Section -->
    <?php
    $features = get_field('product_features');
    if($features):
    ?>
    <section class="container mx-auto px-4 my-16 lg:my-24">
      <h2 class="text-3xl md:text-4xl font-bold text-secColor mb-12 flex items-center gap-4">
        <span>ما الذي يجعل المنتج مختلفاً؟</span>
        <div class="h-1 w-20 bg-mainColor rounded-full hidden md:block"></div>
      </h2>

      <div class="space-y-8">
        <?php foreach($features as $index => $feature):
            $title = $feature['title'];
            $description = $feature['description'];
            $image = $feature['image'];
            $specs = $feature['specs'];
            $bg_style = $feature['background_style'] ?: 'background: linear-gradient(90deg,rgba(0,0,0,0)_0.17%,rgba(0,0,0,0.5)_193.1%,#000_264.18%)';

            // Alternating order
            $is_even = $index % 2 !== 0;
            $order_content = $is_even ? 'md:order-1' : 'md:order-2';
            $order_image = $is_even ? 'md:order-2' : 'md:order-1';
        ?>
        <div class="rounded-2xl py-8 px-6 md:py-10 md:px-12 lg:px-20 flex flex-col md:flex-row items-center gap-8 lg:gap-16 bg-[#f3f4f6]" style="<?php echo esc_attr($bg_style); ?>">

          <!-- Image -->
          <div class="w-full md:w-1/3 order-1 <?php echo $order_image; ?>">
            <div class="flex items-center w-40 h-40 md:w-60 md:h-60 justify-center">
              <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($title); ?>" class="w-full h-full object-contain" />
            </div>
          </div>

          <!-- Content -->
          <div class="flex-1 order-2 <?php echo $order_content; ?>">
            <h3 class="text-2xl lg:text-3xl font-bold text-textColor mb-4">
              <?php echo esc_html($title); ?>
            </h3>
            <p class="text-gray-600 leading-relaxed mb-8 text-lg">
              <?php echo esc_html($description); ?>
            </p>

            <?php if($specs): ?>
            <div>
              <h4 class="font-bold text-secColor mb-4 text-lg">
                المواصفات التقنية:
              </h4>
              <ul class="grid grid-cols-1 lg:grid-cols-2 list-disc marker:text-textColor list-outside gap-2 text-gray-500 ps-5">
                <?php foreach($specs as $spec): ?>
                <li>
                  <span><?php echo esc_html($spec['spec_text']); ?></span>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
    <?php endif; ?>

</div>

<script>
function changeMainImage(url, el) {
    document.getElementById('main-product-image').src = url;
    document.querySelectorAll('.product-thumb').forEach(thumb => {
        thumb.classList.remove('border-mainColor');
        thumb.classList.add('border-transparent');
    });
    el.classList.remove('border-transparent');
    el.classList.add('border-mainColor');
}
</script>

<?php do_action( 'woocommerce_after_single_product' ); ?>
