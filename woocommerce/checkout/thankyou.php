<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.1.0
 */

defined('ABSPATH') || exit;

if ($order):
?>

    <div class="container mx-auto px-4 py-8 woocommerce-order">
        <!-- Thank You Header Card -->
        <div class="bg-[#FFFCF3] rounded-[40px] py-16 px-6 mb-8 text-center flex flex-col items-center justify-center">
            <div class="w-28 h-28 bg-[#FFF5D1] rounded-full flex items-center justify-center mb-6">
                <div class="w-20 h-20 bg-[#FFC107] rounded-full flex items-center justify-center text-white text-4xl shadow-sm">
                    <i class="fa-solid fa-check"></i>
                </div>
            </div>

            <h1 class="text-4xl font-bold text-textColor mb-4"><?php esc_html_e('شكراً لك', 'el-nakaa-theme'); ?></h1>

            <p class="text-xl text-secColor font-medium mb-3">
                <?php esc_html_e('تمت أتمام عملية الدفع بنجاح وطلبك في الطريق', 'el-nakaa-theme'); ?>
            </p>

            <p class="text-gray-400 font-medium text-lg dir-ltr">
                <?php esc_html_e('رقم المعاملة', 'el-nakaa-theme'); ?>:
                <span class="font-bold"><?php echo $order->get_order_number(); ?></span>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Order Details (Right Side) -->
            <div class="lg:col-span-7 bg-white p-4">
                <!-- Products Header -->
                <div class="hidden md:grid grid-cols-12 gap-4 pb-4 px-4 text-gray-500 font-medium text-sm">
                    <div class="col-span-6 text-right"><?php esc_html_e('اسم المنتج', 'el-nakaa-theme'); ?></div>
                    <div class="col-span-3 text-center"><?php esc_html_e('السعر', 'el-nakaa-theme'); ?></div>
                    <div class="col-span-3 text-center"><?php esc_html_e('الكمية', 'el-nakaa-theme'); ?></div>
                </div>

                <?php
                foreach ($order->get_items() as $item_id => $item) {
                    $product = $item->get_product();
                    if (!$product) {
                        continue;
                    }
                ?>
                    <!-- Product Item -->
                    <div class="py-6 border-b border-gray-100 border-dashed">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                            <div class="md:col-span-6 flex items-center gap-4 text-right">
                                <div class="w-16 h-16 bg-[#F7F7F7] rounded-lg flex items-center justify-center p-1 border border-gray-100 shrink-0">
                                    <?php echo $product->get_image('thumbnail', ['class' => 'w-full h-full object-contain mix-blend-multiply']); ?>
                                </div>
                                <h3 class="font-bold text-secColor text-base">
                                    <?php echo wp_kses_post($item->get_name()); ?>
                                </h3>
                            </div>
                            <div class="md:col-span-3 text-center flex justify-between md:flex-col md:gap-1">
                                <span class="md:hidden text-gray-400 text-sm"><?php esc_html_e('السعر:', 'el-nakaa-theme'); ?></span>
                                <span class="font-bold text-secColor md:text-lg">
                                    <?php echo $order->get_formatted_line_subtotal($item); ?>
                                </span>
                            </div>
                            <div class="md:col-span-3 text-center flex justify-between md:flex-col md:gap-1">
                                <span class="md:hidden text-gray-400 text-sm"><?php esc_html_e('الكمية:', 'el-nakaa-theme'); ?></span>
                                <span class="font-bold text-gray-900"><?php echo esc_html($item->get_quantity()); ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <!-- Totals -->
                <div class="mt-8 space-y-4 pt-2">
                    <?php
                    $totals = $order->get_order_item_totals();
                    foreach ($totals as $key => $total) :
                        if ($key === 'payment_method') continue; // Don't show payment method here

                        if ($key === 'order_total') : ?>
                            <div class="flex justify-between items-center text-sm pt-6 border-t border-dashed border-gray-200 mt-6">
                                <span class="text-textColor font-bold text-lg"><?php echo esc_html($total['label']); ?></span>
                                <span class="text-textColor font-bold text-xl"><?php echo wp_kses_post($total['value']); ?></span>
                            </div>
                        <?php else : ?>
                            <div class="flex justify-between items-center text-sm font-bold">
                                <span class="text-secColor"><?php echo esc_html($total['label']); ?></span>
                                <span class="text-secColor"><?php echo wp_kses_post($total['value']); ?></span>
                            </div>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>

            <!-- Summary Card (Left Side) -->
            <div class="lg:col-span-5">
                <div class="bg-[#FFFCF3] rounded-[40px] p-8 h-fit">
                    <h3 class="text-xl font-bold text-secColor mb-8 text-right">
                        <?php esc_html_e('ملخص الطلب', 'el-nakaa-theme'); ?>
                    </h3>

                    <div class="space-y-6 text-right">
                        <!-- Address -->
                        <div class="space-y-1">
                            <address class="text-gray-400 font-medium text-sm not-italic">
                                <?php echo wp_kses_post($order->get_formatted_billing_address()); ?>
                            </address>
                            <?php if ($order->get_billing_phone()) : ?>
                                <p class="text-gray-400 font-medium text-sm dir-ltr text-right">
                                   <?php esc_html_e('رقم الهاتف', 'el-nakaa-theme'); ?>: <?php echo esc_html($order->get_billing_phone()); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="border-t border-gray-200"></div>

                        <!-- Payment Method -->
                        <div>
                            <h4 class="font-bold text-secColor mb-2 text-sm">
                                <?php esc_html_e('طريقة الدفع', 'el-nakaa-theme'); ?>
                            </h4>
                            <p class="text-gray-400 text-sm md:text-xs">
                                <?php echo wp_kses_post($order->get_payment_method_title()); ?>
                            </p>
                        </div>

                        <!-- Delivery Date (Using Order Date as fallback) -->
                        <div>
                            <p class="text-gray-400 text-sm md:text-xs">
                                <?php esc_html_e('تاريخ الطلب:', 'el-nakaa-theme'); ?> <?php echo wc_format_datetime($order->get_date_created()); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php else : ?>

    <div class="container mx-auto px-4 py-8">
        <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'woocommerce'), null); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
    </div>

<?php endif; ?>
