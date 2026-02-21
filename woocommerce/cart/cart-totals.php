<?php
/**
 * Cart totals
 *
 * @package WooCommerce/Templates
 */

defined( 'ABSPATH' ) || exit;

// Ensure styling classes match the user provided HTML exactly
?>
<div class="cart_totals bg-[#FEF9E6] border border-[#FDE047]/30 rounded-[24px] p-6 lg:p-8 sticky top-24 <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

    <h3 class="text-2xl font-bold text-secColor mb-6 text-center"><?php esc_html_e( 'ملخص العربة', 'woocommerce' ); ?></h3>
    <div class="w-full border-t border-gray-300 mb-6"></div>

    <?php do_action( 'woocommerce_before_cart_totals' ); ?>

    <div class="space-y-4 mb-8">
        <!-- Subtotal -->
        <div class="flex justify-between items-center text-gray-500 text-sm font-bold cart-subtotal">
            <span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
            <span data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <!-- Shipping -->
        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

            <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

            <?php wc_cart_totals_shipping_html(); ?>

            <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

        <?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

            <div class="shipping">
                <div class="flex justify-between items-center text-gray-500 text-sm font-bold">
                    <span><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></span>
                    <span><?php woocommerce_shipping_calculator(); ?></span>
                </div>
            </div>

        <?php endif; ?>

        <!-- Fees -->
        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="flex justify-between items-center text-gray-500 text-sm font-bold fee">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <!-- Tax -->
        <?php
        if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
            $taxable_address = WC()->customer->get_taxable_address();
            $estimated_text  = '';

            if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                /* translators: %s location. */
                $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
            }

            if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                    ?>
                    <div class="flex justify-between items-center text-gray-500 text-sm font-bold tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <span><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <span data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="flex justify-between items-center text-gray-500 text-sm font-bold tax-total">
                    <span><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                    <span data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
                <?php
            }
        }
        ?>

        <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

        <!-- Total -->
        <div class="flex justify-between items-center text-[#937801] font-extrabold text-lg pt-4 order-total">
            <span><?php esc_html_e( 'الدفع', 'woocommerce' ); ?></span>
            <span data-title="<?php esc_attr_e( 'الدفع', 'woocommerce' ); ?>"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

    </div>

    <div class="wc-proceed-to-checkout">
        <!-- Manually creating button to match HTML structure -->
        <button class="w-full bg-mainColor text-secColor font-bold py-3.5 rounded-xl hover:bg-yellow-500 transition-all shadow-lg shadow-yellow-500/10 active:scale-95 cursor-pointer">
            <?php esc_html_e( 'الدفع', 'woocommerce' ); ?>
        </button>
    </div>

    <?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
