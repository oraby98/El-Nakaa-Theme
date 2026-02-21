<?php
/**
 * El Nakaa Page Title Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-page-title-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-page-title';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$title = get_field('page_title') ?: 'عن نقاوة';
$description = get_field('page_title_description');
$bg_image = get_field('page_title_bg_image');
$bg_image_url = $bg_image ? $bg_image['url'] : '';

?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> relative min-h-125 flex items-center justify-center bg-gray-900 overflow-visible">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center z-0" style="background-image: url('<?php echo esc_url($bg_image_url); ?>');"></div>

    <!-- Dark Overlay -->
    <div class="absolute inset-0 bg-black/60 z-10"></div>

    <!-- Hero Content -->
    <div class="container mx-auto px-4 relative z-20 text-center text-white pb-32 pt-12">
        <h2 class="text-4xl md:text-6xl font-bold mb-4"><?php echo esc_html($title); ?></h2>
		  <?php if (!empty($description)) : ?>
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto">
                <?php echo esc_html($description); ?>
            </p>
        <?php endif; ?>
    </div>
</section>
