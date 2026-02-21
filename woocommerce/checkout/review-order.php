<?php
/**
 * Review Order (Totals Only)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bg-[#FEF9E6] border border-[#FDE047]/30 rounded-3xl p-6 sticky top-24">
    <h3 class="text-xl font-bold text-secColor mb-4 text-center">
      ملخص الطلب
    </h3>
    <div class="w-full border-t border-gray-300 mb-4"></div>

    <div class="space-y-4 mb-6">
      <div class="flex justify-between items-center text-gray-500 text-sm font-medium">
        <span>المجموع الفرعي</span>
        <span><?php wc_cart_totals_subtotal_html(); ?></span>
      </div>

      <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <div class="flex justify-between items-center text-gray-500 text-sm font-medium coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
            <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
        </div>
      <?php endforeach; ?>

      <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <div class="flex justify-between items-center text-gray-500 text-sm font-medium">
            <span><?php echo esc_html( $fee->name ); ?></span>
            <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
        </div>
      <?php endforeach; ?>

      <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
        <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
        <?php // Simplified Shipping Display - ideally loop packages but checking cart totals usually suffices for simple setups ?>
        <div class="flex justify-between items-center text-gray-500 text-sm font-medium">
             <span>الشحن</span>
             <span><?php
                // Using standard cart total shipping html
                wc_cart_totals_shipping_html();
             ?></span>
        </div>
        <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
      <?php endif; ?>

      <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
         <div class="flex justify-between items-center text-gray-500 text-sm font-medium">
            <span><?php echo esc_html( $tax->label ); ?></span>
            <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
         </div>
      <?php endforeach; ?>

      <div class="flex justify-between items-center text-[#937801] font-bold text-lg pt-2">
        <span>الإجمالي</span>
        <span><?php wc_cart_totals_order_total_html(); ?></span>
      </div>
    </div>
</div>
