<?php
/**
 * Block Name: El Nakaa Info Cards
 *
 * This is the template that displays the El Nakaa Info Cards block (Contact & Spare Parts).
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-info-cards-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-info-cards';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Card 1 Fields (Contact Us)
$card1_tag = get_field('card_1_tag') ?: 'دعم احترافي';
$card1_title = get_field('card_1_title') ?: 'تواصل معنا';
$card1_desc = get_field('card_1_description') ?: 'فريق الدعم الفني جاهز لمساعدتك على مدار الساعة';
$card1_btn_text = get_field('card_1_button_text') ?: 'تواصل الآن';
$card1_btn_url = get_field('card_1_button_url') ?: '#';
$card1_bg = get_field('card_1_bg_image');
$card1_bg_url = $card1_bg ? $card1_bg['url'] : get_theme_file_uri('assets/images/3.png');

// Card 2 Fields (Spare Parts)
$card2_tag = get_field('card_2_tag') ?: 'قطع أصلية';
$card2_title = get_field('card_2_title') ?: 'قطع الغيار';
$card2_desc = get_field('card_2_description') ?: 'قطع غيار أصلية لجميع منتجات نقاوة مع ضمان الجودة';
$card2_btn_text = get_field('card_2_button_text') ?: 'تصفح القطع';
$card2_btn_url = get_field('card_2_button_url') ?: '#';
$card2_bg = get_field('card_2_bg_image');
$card2_bg_url = $card2_bg ? $card2_bg['url'] : get_theme_file_uri('assets/images/2.png');

?>

<section
  id="<?php echo esc_attr($id); ?>"
  class="<?php echo esc_attr($className); ?> container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 my-8 md:my-12 xl:my-16"
  style="--card-1-bg: url('<?php echo esc_url($card1_bg_url); ?>'); --card-2-bg: url('<?php echo esc_url($card2_bg_url); ?>');"
>
  <!-- Contact Us Card (Dark) -->
  <div
    class="rounded-2xl p-6 md:p-8 xl:p-12 flex flex-col items-start justify-center text-white bg-cover bg-center bg-no-repeat min-h-[250px] xl:min-h-[300px] bg-[linear-gradient(0deg,rgba(16,24,40,0.80)_0%,rgba(16,24,40,0.80)_100%),var(--card-1-bg)]"
  >
    <span
      class="text-gray-200 px-4 py-1.5 rounded-full text-xs md:text-sm font-medium mb-4"
    >
      <?php echo esc_html($card1_tag); ?>
    </span>
    <h2 class="text-2xl md:text-3xl xl:text-4xl font-bold mb-3">
      <?php echo esc_html($card1_title); ?>
    </h2>
    <p class="text-gray-300 mb-6 xl:mb-8 max-w-md text-base xl:text-lg">
      <?php echo esc_html($card1_desc); ?>
    </p>
    <?php if ($card1_btn_text && $card1_btn_url) : ?>
    <a
      href="<?php echo esc_url($card1_btn_url); ?>"
      class="bg-white text-secColor px-6 xl:px-8 py-2.5 xl:py-3 rounded-lg font-bold hover:bg-gray-100 transition-colors w-full md:w-auto text-sm xl:text-base text-center inline-block"
    >
      <?php echo esc_html($card1_btn_text); ?>
    </a>
    <?php endif; ?>
  </div>

  <!-- Spare Parts Card (Light) -->
  <div
    class="rounded-2xl p-6 md:p-8 xl:p-12 flex flex-col items-start justify-center text-secColor bg-cover bg-center bg-no-repeat min-h-[250px] xl:min-h-[300px] bg-[linear-gradient(0deg,rgba(255,255,255,0.80)_0%,rgba(255,255,255,0.80)_100%),var(--card-2-bg)]"
  >
    <span
      class="text-gray-700 px-4 py-1.5 rounded-full text-xs md:text-sm font-medium mb-4"
    >
      <?php echo esc_html($card2_tag); ?>
    </span>
    <h2 class="text-2xl md:text-3xl xl:text-4xl font-bold mb-3">
      <?php echo esc_html($card2_title); ?>
    </h2>
    <p class="text-gray-700 mb-6 xl:mb-8 max-w-md text-base xl:text-lg">
      <?php echo esc_html($card2_desc); ?>
    </p>
    <?php if ($card2_btn_text && $card2_btn_url) : ?>
    <a
      href="<?php echo esc_url($card2_btn_url); ?>"
      class="bg-secColor text-white px-6 xl:px-8 py-2.5 xl:py-3 rounded-lg font-bold hover:bg-gray-800 transition-colors w-full md:w-auto text-sm xl:text-base text-center inline-block"
    >
      <?php echo esc_html($card2_btn_text); ?>
    </a>
    <?php endif; ?>
  </div>
</section>
