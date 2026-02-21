<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the form is not shown.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <section class="container mx-auto py-10 px-4 min-h-[60vh]">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Order Summary (Left Sidebar) -->
        <div class="w-full lg:w-1/4 order-1 lg:order-2">
			<!-- Order Review -->
			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action( 'woocommerce_checkout_order_review' ); ?>
			</div>
        </div>

        <!-- Main Content (Right Side) -->
        <div class="w-full lg:w-3/4 order-2 lg:order-1 space-y-6">
          <!-- Customer Data Card -->
          <div class="bg-white border border-gray-200 rounded-3xl p-6 md:p-8">
            <div class="flex items-center gap-2 mb-2">
              <i class="fa-regular fa-file-lines text-xl text-gray-700"></i>
              <h2 class="text-2xl font-bold text-secColor">بيانات العميل</h2>
            </div>
            <p class="text-gray-400 text-sm mb-8 pr-7">
              نستخدم هذه المعلومات فقط لتجهيز وشحن طلبك
            </p>

            <div class="space-y-6">
              <!-- Name & Phone -->
              <div class="grid md:grid-cols-2 gap-6">
                 <!-- Full Name -->
                 <div class="input-group">
					<input type="text" class="input" required name="billing_first_name" id="billing_first_name" placeholder=" " value="<?php echo esc_attr( $checkout->get_value( 'billing_first_name' ) ); ?>" autocomplete="given-name">
					<!-- Hidden Last Name -->
					<input type="hidden" name="billing_last_name" value=".">
					<label for="billing_first_name" class="user-label">الاسم بالكامل</label>
                 </div>
                 <!-- Phone -->
                 <div class="input-group">
					<input type="tel" class="input" required name="billing_phone" id="billing_phone" placeholder=" " value="<?php echo esc_attr( $checkout->get_value( 'billing_phone' ) ); ?>" autocomplete="tel">
					<label for="billing_phone" class="user-label">رقم الهاتف</label>
                 </div>
              </div>

              <!-- Country & City -->
               <div class="grid md:grid-cols-2 gap-6">
                  <!-- Country -->
                  <div class="input-group relative">
                    <select name="billing_country" id="billing_country" class="input appearance-none cursor-pointer" required>
						<option value="" disabled selected></option>
                        <?php
                            $countries = WC()->countries->get_shipping_countries();
                            foreach($countries as $code => $name) {
                                echo '<option value="'.$code.'" '.selected($checkout->get_value('billing_country'), $code, false).'>'.$name.'</option>';
                            }
                        ?>
					</select>
                    <label for="billing_country" class="user-label">الدولة</label>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none text-mainColor">
                        <i class="fa-solid fa-caret-down"></i>
                    </div>
                  </div>
                   <!-- City -->
                  <div class="input-group relative">
                     <input type="text" name="billing_city" id="billing_city" class="input" required placeholder=" " value="<?php echo esc_attr( $checkout->get_value( 'billing_city' ) ); ?>">
                     <label for="billing_city" class="user-label">المدينة / المنطقة</label>
                  </div>
               </div>

              <!-- Address -->
              <div class="input-group">
                <input type="text" name="billing_address_1" id="billing_address_1" class="input" required placeholder=" " value="<?php echo esc_attr( $checkout->get_value( 'billing_address_1' ) ); ?>">
                <label for="billing_address_1" class="user-label">العنوان التفصيلي</label>
              </div>

              <!-- Notes -->
              <div class="input-group">
                <input type="text" name="order_comments" id="order_comments" class="input" placeholder=" " value="<?php echo esc_attr( $checkout->get_value( 'order_comments' ) ); ?>">
                <label for="order_comments" class="user-label">ملاحظات التوصيل</label>
              </div>
            </div>
          </div>

          <!-- Payment Card -->
          <div class="bg-white border border-gray-200 rounded-3xl p-6 md:p-8">
            <div class="flex items-center gap-2 mb-6">
              <i class="fa-regular fa-credit-card text-xl text-gray-700"></i>
              <h2 class="text-2xl font-bold text-secColor">الدفع</h2>
            </div>
            <div class="border-t border-gray-100 mb-6"></div>

            <?php woocommerce_checkout_payment(); ?>
          </div>

            <!-- Terms & Submit -->
              <div class="space-y-4">
               <?php if ( wc_get_page_id( 'terms' ) > 0 && apply_filters( 'woocommerce_checkout_show_terms', true ) ) : ?>
                <div class="flex items-start gap-3">
                  <div class="relative flex items-center">
                    <input type="checkbox" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 bg-white checked:bg-mainColor checked:border-mainColor transition-all" name="terms" id="terms" checked />
                    <i class="fa-solid fa-check absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                  </div>
                  <label for="terms" class="text-sm font-bold text-secColor leading-5">
                    أوافق على الشروط والأحكام, وأقر بمراجعتي للمحتوى والموافقة على
                    تنفيذ الطلب وتفعيل تجربة «أثر».
                    <br />
                    <span class="text-gray-500 font-normal">لن يتم الطباعة أو التفعيل إلا بعد تأكيدك.</span>
                  </label>
                </div>
               <?php endif; ?>

                <button type="submit" class="w-full bg-mainColor text-secColor font-bold py-4 rounded-xl hover:bg-yellow-500 transition-all shadow-lg shadow-yellow-500/10 active:scale-95 cursor-pointer text-lg" name="woocommerce_checkout_place_order" id="place_order" value="اتمام عملية الشراء" data-value="اتمام عملية الشراء">
                  اتمام عملية الشراء
                </button>
             </div>
             <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>

        </div> <!-- End Right Side -->
      </div>
    </section>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
