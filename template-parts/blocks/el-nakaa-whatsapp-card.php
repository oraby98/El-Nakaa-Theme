<?php
/**
 * El Nakaa WhatsApp Card Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-whatsapp-card-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-whatsapp-card';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$title = get_field('whatsapp_title') ?: 'راسلنا مباشرة عبر واتساب';
$text = get_field('whatsapp_text') ?: 'أسرع وأسهل طريقة للتواصل معنا. فريقنا بانتظارك على مدار الساعة لتقديم الدعم المطلوب';
$btn_text = get_field('whatsapp_btn_text') ?: 'ابدأ المحادثة الآن';
$btn_url = get_field('whatsapp_btn_url') ?: '#';
$footer_text = get_field('whatsapp_footer_text') ?: 'معتاد الرد خلال دقائق';

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> mt-20 w-full px-4">
    <div class="container mx-auto mb-10">
        <div class="bg-[#F0FFF4] rounded-2xl shadow-xl p-8 md:p-20 text-center border border-green-100">
            <div class="w-16 h-16 bg-[#25D366] rounded-full flex items-center justify-center mx-auto mb-6 text-white text-3xl shadow-lg shadow-green-500/30">
                <i class="fa-brands fa-whatsapp"></i>
            </div>

            <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">
                <?php echo esc_html($title); ?>
            </h2>
            <p class="text-gray-500 mb-8 max-w-lg mx-auto">
                <?php echo esc_html($text); ?>
            </p>

            <a href="<?php echo esc_url($btn_url); ?>" class="inline-flex items-center justify-center gap-2 bg-[#25D366] text-white px-8 py-3 rounded-lg font-bold hover:bg-[#20bd5a] transition-colors shadow-lg shadow-green-500/20 text-lg">
                <i class="fa-regular fa-comment-dots"></i>
                <span><?php echo esc_html($btn_text); ?></span>
            </a>

            <p class="text-gray-400 text-xs mt-4">
                <i class="fa-regular fa-clock"></i>
                <?php echo esc_html($footer_text); ?>
            </p>
        </div>
    </div>
</div>
