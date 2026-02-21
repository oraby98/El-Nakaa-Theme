<?php
/**
 * Block Name: El Nakaa Hero Section
 *
 * This is the template that displays the El Nakaa Hero Section block.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'el-nakaa-hero-section-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'el-nakaa-hero-section';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$bg_image = get_field('hero_bg_image');
$bg_url = $bg_image ? $bg_image['url'] : get_theme_file_uri('assets/images/hero.png');

$title = get_field('hero_title') ?: 'بيور';
$description = get_field('hero_description') ?: 'مجموعة منتجات بيور من نقاوة تجمع بين الأداء العالي والتصميم النقي لتناسب كل بيت عصري.';
$button_1_text = get_field('hero_button_1_text') ?: 'تسوق الآن';
$button_1_url = get_field('hero_button_1_url') ?: '#';
$button_2_text = get_field('hero_button_2_text') ?: 'اكتشف المزيد';
$button_2_url = get_field('hero_button_2_url') ?: '#';
$stats = get_field('hero_stats');

?>
<!-- start Hero -->
<section
  id="<?php echo esc_attr($id); ?>"
  class="<?php echo esc_attr($className); ?> py-12 h-screen max-md:h-[70vh] lg:bg-[image:var(--hero-bg),linear-gradient(269deg,#f2f2f2_36.42%,#a6a49f_99.55%)] bg-[linear-gradient(269deg,#f2f2f2_36.42%,#a6a49f_99.55%)] bg-no-repeat bg-cover bg-center relative"
  style="--hero-bg: url('<?php echo esc_url($bg_url); ?>');"
>
  <div class="container mx-auto px-4 h-full flex items-center">
    <div class="max-w-xl">
      <h1 class="text-7xl md:text-8xl text-mainColor mb-6 relative w-fit">
        <?php echo esc_html($title); ?>
        <div class="mt-4">
          <svg
            width="161"
            height="4"
            viewBox="0 0 161 4"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M0 2C0 0.895431 0.895431 0 2 0H158.406C159.511 0 160.406 0.895431 160.406 2C160.406 3.10457 159.511 4 158.406 4H2C0.89543 4 0 3.10457 0 2Z"
              fill="url(#paint0_linear_1_103)"
            />
            <defs>
              <linearGradient
                id="paint0_linear_1_103"
                x1="160.406"
                y1="2"
                x2="0"
                y2="2"
                gradientUnits="userSpaceOnUse"
              >
                <stop stop-color="#FECF00" stop-opacity="0" />
                <stop
                  offset="0.5"
                  stop-color="#FECF00"
                  stop-opacity="0.5"
                />
                <stop
                  offset="0.75"
                  stop-color="#FECF00"
                  stop-opacity="0.75"
                />
                <stop offset="1" stop-color="#FECF00" />
              </linearGradient>
            </defs>
          </svg>
        </div>
      </h1>
      <p class="text-xl md:text-2xl text-gray-800 leading-relaxed mb-9">
        <?php echo esc_html($description); ?>
      </p>

      <div class="flex flex-wrap gap-4 mb-9">
        <?php if ($button_1_text && $button_1_url) : ?>
        <a
          href="<?php echo esc_url($button_1_url); ?>"
          class="bg-mainColor text-secColor px-10 py-3.5 rounded-lg font-bold hover:bg-yellow-400 transition-colors text-lg min-w-[160px] text-center inline-block"
        >
          <?php echo esc_html($button_1_text); ?>
        </a>
        <?php endif; ?>
        <?php if ($button_2_text && $button_2_url) : ?>
        <a
          href="<?php echo esc_url($button_2_url); ?>"
          class="bg-white border-2 border-mainColor text-secColor px-10 py-3.5 rounded-lg font-bold hover:bg-gray-50 transition-colors text-lg min-w-[160px] text-center inline-block"
        >
          <?php echo esc_html($button_2_text); ?>
        </a>
        <?php endif; ?>
      </div>

      <div class="flex gap-8 md:gap-16">
        <?php if ($stats) : ?>
            <?php foreach ($stats as $stat) : ?>
                <div class="text-center">
                  <h3 class="text-4xl font-bold text-mainColor mb-2" dir="ltr">
                    <?php echo esc_html($stat['number']); ?>
                  </h3>
                  <p class="text-gray-800 font-bold"><?php echo esc_html($stat['label']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<!-- end of hero -->
