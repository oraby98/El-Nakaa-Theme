<?php
/**
 * El Nakaa Social Follow Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-social-follow-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-social-follow';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values.
$title = get_field('social_title') ?: 'تابعنا على وسائل التواصل';
$text = get_field('social_text') ?: 'ابق على اطلاع بآخر منتجاتنا وعروضنا الحصرية';
$links = get_field('social_links');

?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> container mx-auto px-4 mb-20 text-center">
    <h3 class="text-2xl font-bold text-secColor mb-6">
        <?php echo esc_html($title); ?>
    </h3>
    <p class="text-gray-500 mb-8">
        <?php echo esc_html($text); ?>
    </p>

    <div class="flex justify-center gap-4">
        <?php if($links): ?>
            <?php foreach($links as $link):
                $url = $link['url'];
                $icon = $link['icon_class'];
                $style = $link['style']; // 'light' (yellow bg) or 'solid' (mainColor bg)

                $classes = '';
                if($style === 'solid') {
                    $classes = 'bg-mainColor text-white hover:bg-yellow-500 shadow-lg shadow-yellow-500/20';
                } else {
                    $classes = 'bg-[#FEF9C3] text-mainColor hover:bg-mainColor hover:text-white';
                }
            ?>
            <a href="<?php echo esc_url($url); ?>" class="w-12 h-12 rounded-full flex items-center justify-center transition-all text-xl <?php echo $classes; ?>">
                <i class="<?php echo esc_attr($icon); ?>"></i>
            </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
