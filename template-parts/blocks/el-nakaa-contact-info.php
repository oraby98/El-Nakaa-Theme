<?php
/**
 * El Nakaa Contact Info Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-contact-info-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-contact-info';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values.
$title = get_field('contact_info_title') ?: 'معلومات التواصل';
$items = get_field('contact_info_items');

?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> bg-gray-50 py-20 my-20">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-secColor text-center mb-12">
            <?php echo esc_html($title); ?>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if($items): ?>
                <?php foreach($items as $item):
                    $icon = $item['icon_class'];
                    $item_title = $item['title'];
                    $desc = $item['description'];
                    $theme = $item['theme']; // blue, green, purple

                    // Define theme classes
                    $bg_class = 'bg-' . $theme . '-50';
                    $text_class = 'text-' . $theme . '-500';
                    $hover_bg = 'group-hover:bg-' . $theme . '-500';
                    $hover_text = 'group-hover:text-white';
                ?>
                <div class="bg-white border boundary-gray-100 rounded-2xl p-8 hover:shadow-lg transition-shadow text-center group">
                    <div class="w-14 h-14 <?php echo $bg_class . ' ' . $text_class; ?> rounded-full flex items-center justify-center mx-auto mb-6 text-2xl <?php echo $hover_bg . ' ' . $hover_text; ?> transition-colors">
                        <i class="<?php echo esc_attr($icon); ?>"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3"><?php echo esc_html($item_title); ?></h3>
                    <div class="text-gray-500 leading-relaxed">
                        <?php echo $desc; // Allow HTML for breaks ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
