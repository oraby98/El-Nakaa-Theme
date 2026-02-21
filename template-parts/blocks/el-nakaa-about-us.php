<?php
/**
 * El Nakaa About Us Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   int|string $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-about-us-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-about-us';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// === About Section Fields ===
$about_tagline = get_field('about_tagline') ?: 'قصتنا';
$about_title = get_field('about_title') ?: 'من ورشة صغيرة إلى مصنع رائد';
$about_content = get_field('about_content') ?: '<p class="text-gray-500 text-base leading-relaxed mb-4">بدأت نقاوة في عام 1998...</p>';
$about_image = get_field('about_image');
$about_image_url = $about_image ? $about_image['url'] : '/img/about2.png';

// === Vision & Mission Fields ===
$vision_title = get_field('vision_title') ?: 'رؤيتنا';
$vision_text = get_field('vision_text') ?: 'أن نكون الخيار الأول للأسرة المصرية...';
$vision_icon_class = get_field('vision_icon_class') ?: 'fa-regular fa-eye';

$mission_title = get_field('mission_title') ?: 'رسالتنا';
$mission_text = get_field('mission_text') ?: 'تصنيع أجهزة كهربائية عالية الجودة...';
$mission_icon_class = get_field('mission_icon_class') ?: 'fa-solid fa-bullseye';

// === Core Values Fields ===
$values_title = get_field('values_title') ?: 'قيمنا الأساسية';
$values_description = get_field('values_description') ?: 'المبادئ التي نؤمن بها...';
$values = get_field('values_list');

// === CTA Section Fields ===
$cta_title = get_field('cta_title') ?: 'انضم إلى عائلة نقاوة';
$cta_text = get_field('cta_text') ?: 'اكتشف منتجاتنا وكن جزءاً من قصة النجاح المصرية';
$cta_btn1_text = get_field('cta_btn1_text') ?: 'تصفح المنتجات';
$cta_btn1_url = get_field('cta_btn1_url') ?: '#';
$cta_btn2_text = get_field('cta_btn2_text') ?: 'تواصل معنا';
$cta_btn2_url = get_field('cta_btn2_url') ?: '#';

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">

    <!-- About Section -->
    <section class="container mx-auto px-4 py-16 xl:py-24">
      <div class="flex flex-col lg:flex-row items-center gap-8 bg-gray-50">
        <!-- Content -->
        <div class="w-full lg:w-1/2 text-center lg:text-start lg:ps-10">
          <span class="inline-block bg-mainColor text-secColor px-6 py-2 rounded-full font-bold mb-6">
            <?php echo esc_html($about_tagline); ?>
          </span>
          <h2 class="text-3xl font-bold text-secColor mb-6 leading-tight">
            <?php echo esc_html($about_title); ?>
          </h2>
          <div class="text-gray-500 text-base leading-relaxed mb-4">
             <?php echo $about_content; ?>
          </div>
        </div>
        <!-- Image -->
        <div class="w-full lg:w-1/2">
          <div class="relative rounded-2xl overflow-hidden">
            <img src="<?php echo esc_url($about_image_url); ?>" alt="<?php echo esc_attr($about_title); ?>" class="w-full h-auto object-cover min-h-[400px]" />
          </div>
        </div>
      </div>
    </section>

    <!-- Vision & Mission -->
    <section class="container mx-auto px-4 mb-24">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Vision - Dark/Primary -->
        <div class="bg-mainColor rounded-3xl p-10 flex flex-col shadow-lg shadow-yellow-500/20">
          <div class="w-16 h-16 bg-white text-mainColor rounded-full flex items-center justify-center text-2xl mb-6 shadow-sm">
            <i class="<?php echo esc_attr($vision_icon_class); ?>"></i>
          </div>
          <h3 class="text-2xl font-bold text-secColor mb-4"><?php echo esc_html($vision_title); ?></h3>
          <p class="text-secColor leading-relaxed font-medium">
            <?php echo esc_html($vision_text); ?>
          </p>
        </div>

        <!-- Mission - Light -->
        <div class="bg-[#FFF9EA] rounded-3xl p-10 flex flex-col border border-[#FEEBA5]">
          <div class="w-16 h-16 bg-mainColor text-white rounded-full flex items-center justify-center text-2xl mb-6 shadow-md shadow-yellow-500/20">
            <i class="<?php echo esc_attr($mission_icon_class); ?>"></i>
          </div>
          <h3 class="text-2xl font-bold text-secColor mb-4"><?php echo esc_html($mission_title); ?></h3>
          <p class="text-gray-600 leading-relaxed font-medium">
            <?php echo esc_html($mission_text); ?>
          </p>
        </div>
      </div>
    </section>

    <!-- Core Values -->
    <section class="bg-gray-50 py-12 lg:py-20">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-3xl lg:text-4xl font-bold text-secColor mb-4">
            <?php echo esc_html($values_title); ?>
          </h2>
          <p class="text-gray-500 text-lg">
            <?php echo esc_html($values_description); ?>
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <?php if($values): ?>
            <?php foreach($values as $value):
                $icon_class = $value['icon_class'];
                $val_title = $value['title'];
                $val_desc = $value['description'];
            ?>
              <div class="bg-white border flex items-start gap-4 border-gray-100 rounded-2xl p-8 shadow-lg transition-shadow text-start group">
                <div>
                  <div class="w-12 h-12 bg-yellow-50 text-mainColor rounded-xl flex items-center justify-center mx-auto mb-6 text-xl group-hover:bg-mainColor group-hover:text-white transition-colors">
                    <i class="<?php echo esc_attr($icon_class); ?>"></i>
                  </div>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-secColor mb-3"><?php echo esc_html($val_title); ?></h3>
                  <p class="text-gray-500 text-sm leading-relaxed">
                    <?php echo esc_html($val_desc); ?>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="relative overflow-hidden py-20 lg:py-28" style="background: linear-gradient(180deg, #fdc700 0%, #332900 100%), linear-gradient(135deg, #155dfc 0%, #1447e6 100%);">
      <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
          <?php echo esc_html($cta_title); ?>
        </h2>
        <p class="text-white/90 text-xl mb-10 max-w-2xl mx-auto">
          <?php echo esc_html($cta_text); ?>
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
          <a href="<?php echo esc_url($cta_btn1_url); ?>" class="bg-mainColor text-secColor px-12 py-1 rounded-lg font-bold hover:bg-[#ffe14d] transition-colors text-lg shadow-lg shadow-black/10">
            <?php echo esc_html($cta_btn1_text); ?>
          </a>
          <a href="<?php echo esc_url($cta_btn2_url); ?>" class="bg-white text-secColor px-12 py-1 rounded-lg font-bold hover:bg-gray-50 transition-colors text-lg shadow-lg shadow-black/10">
             <?php echo esc_html($cta_btn2_text); ?>
          </a>
        </div>
      </div>
    </section>

</div>
