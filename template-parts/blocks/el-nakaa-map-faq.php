<?php
/**
 * El Nakaa Map & FAQ Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-map-faq-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-map-faq';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values.
$map_iframe = get_field('map_iframe_code');
$faq_title = get_field('faq_title') ?: 'الأسئلة الشائعة';
$faq_subtitle = get_field('faq_subtitle') ?: 'إجابات سريعة على أكثر الأسئلة شيوعاً';
$faqs = get_field('faq_list');

?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?> mx-auto px-4 mb-24">
    <!-- Map -->
    <div class="bg-gray-100 py-10 w-full shadow-sm mb-20 relative">
        <div class="container h-100 rounded-3xl overflow-hidden min-h-[400px]">
            <?php if($map_iframe): ?>
                <?php echo $map_iframe; ?>
            <?php else: ?>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d110502.60389552708!2d31.63006456225645!3d30.29649983756263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1457f70b4395690b%3A0xe48722154564506c!2s10th%20of%20Ramadan%20City%2C%20Ash%20Sharqia%20Governorate!5e0!3m2!1sen!2seg!4v1706950000000!5m2!1sen!2seg" width="100%" height="100%" style="border: 0" allowfullscreen="" loading="lazy" class="grayscale-[0.2]"></iframe>
            <?php endif; ?>
        </div>
    </div>

    <!-- FAQ Headers -->
    <div class="text-center container mb-12">
        <div class="w-16 h-16 bg-white border border-yellow-100 text-mainColor rounded-full flex items-center justify-center mx-auto mb-6 text-3xl shadow-sm">
            <i class="fa-regular fa-circle-question"></i>
        </div>
        <h2 class="text-3xl font-bold text-secColor mb-3"><?php echo esc_html($faq_title); ?></h2>
        <p class="text-gray-400"><?php echo esc_html($faq_subtitle); ?></p>
    </div>

    <!-- FAQ Accordion -->
    <div class="max-w-4xl mx-auto space-y-4" id="faq-accordion">
        <?php if($faqs): ?>
            <?php foreach($faqs as $index => $faq):
                $question = $faq['question'];
                $answer = $faq['answer'];
                $number = $index + 1;
                // First item active by default? user html has item 1 active.
                $isActive = $index === 0 ? 'active' : '';
            ?>
            <div class="faq-item group <?php echo $isActive; ?>">
                <button class="w-full px-6 py-5 flex items-center justify-between gap-4 text-start bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-300 group-[.active]:bg-white group-[.active]:shadow-sm border border-transparent group-[.active]:border-gray-100">
                    <div class="flex items-center gap-4">
                        <span class="w-8 h-8 flex items-center justify-center bg-mainColor text-white font-bold rounded-full text-sm">
                            <?php echo $number; ?>
                        </span>
                        <span class="font-bold text-secColor text-lg">
                            <?php echo esc_html($question); ?>
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-mainColor transition-transform duration-300 group-[.active]:rotate-180"></i>
                </button>
                <div class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out group-[.active]:grid-rows-[1fr]">
                    <div class="overflow-hidden">
                        <p class="px-6 pb-6 pt-2 text-gray-500 leading-relaxed ps-16 text-sm">
                            <?php echo $answer; // Allow HTML in answer ?>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
