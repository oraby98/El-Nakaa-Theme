<?php
/**
 * The footer
 *
 * @package Bathe
 */

?>
    <!-- start of footer -->
    <footer>
      <!-- Features Section -->
      <div class="bg-white py-12 border-t border-gray-100">
        <div class="container mx-auto px-4">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            if( have_rows('footer_features', 'option') ):
                while( have_rows('footer_features', 'option') ) : the_row();
                    $icon_class = get_sub_field('icon_class');
                    $title = get_sub_field('title');
                    $subtitle = get_sub_field('subtitle');
                    ?>
                    <div class="flex items-center gap-4 text-start">
                      <div
                        class="w-12 h-12 bg-secColor rounded-lg flex items-center justify-center text-white text-xl"
                      >
                        <i class="<?php echo esc_attr($icon_class); ?>"></i>
                      </div>
                      <div>
                        <h3 class="font-bold text-lg text-secColor"><?php echo esc_html($title); ?></h3>
                        <p class="text-gray-500 text-sm"><?php echo esc_html($subtitle); ?></p>
                      </div>
                    </div>
                    <?php
                endwhile;
            endif;
            ?>
          </div>
        </div>
      </div>

      <!-- Main Footer -->
      <div class="bg-secColor text-white md:pt-16 pt-8 pb-8">
        <div class="container mx-auto px-4">
          <div
            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12"
          >
            <!-- Column 1: About -->
            <div class="text-center md:text-start">
              <div class="mb-6 flex justify-center md:justify-start">
                <?php
                $footer_logo = get_field('footer_logo', 'option');
                if($footer_logo): ?>
                    <img src="<?php echo esc_url($footer_logo); ?>" class="w-22" alt="logo" />
                <?php else: ?>
                    <img src="<?php echo get_theme_file_uri('assets/images/logo.png'); ?>" class="w-22" alt="logo" />
                <?php endif; ?>
              </div>
              <p class="text-gray-400 text-sm leading-relaxed mb-6">
                <?php echo esc_html(get_field('footer_about_text', 'option')); ?>
              </p>
              <div class="flex justify-center md:justify-start gap-2">
                <?php
                if( have_rows('footer_socials', 'option') ):
                    while( have_rows('footer_socials', 'option') ) : the_row();
                        $social_icon = get_sub_field('icon_class');
                        $social_url = get_sub_field('url');
                        ?>
                        <a
                          href="<?php echo esc_url($social_url); ?>"
                          class="w-10 h-10 bg-gray-500 rounded flex items-center justify-center hover:bg-mainColor hover:text-secColor transition-colors"
                          ><i class="<?php echo esc_attr($social_icon); ?>"></i
                        ></a>
                        <?php
                    endwhile;
                endif;
                ?>
              </div>
            </div>

            <!-- Column 2: Contact -->
            <div class="">
              <h4 class="text-xl font-bold mb-6">تواصل معنا</h4>
              <ul class="space-y-4 text-gray-400">
                <li class="flex items-center gap-3">
                  <i class="fa-solid fa-location-dot text-mainColor"></i>
                  <span><?php echo esc_html(get_field('footer_address', 'option')); ?></span>
                </li>
                <li class="flex items-center gap-3">
                  <i class="fa-solid fa-phone text-mainColor"></i>
                  <span dir="ltr"><?php echo esc_html(get_field('footer_phone', 'option')); ?></span>
                </li>
                <li class="flex items-center gap-3">
                  <i class="fa-regular fa-envelope text-mainColor"></i>
                  <span><?php echo esc_html(get_field('footer_email', 'option')); ?></span>
                </li>
              </ul>
            </div>

            <!-- Column 3: Quick Links -->
            <div class="">
              <h4 class="text-xl font-bold mb-6">روابط سريعة</h4>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer_quick_links',
                    'container'      => false,
                    'menu_class'     => 'space-y-3 text-gray-400',
                    'fallback_cb'    => false,
                    'link_before'    => '<i class="fa-solid fa-arrow-left text-xs ml-2"></i> ', // Font Awesome Arrow
                    'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>'
                ) );
                ?>
            </div>

            <!-- Column 4: Categories -->
            <div class="">
              <h4 class="text-xl font-bold mb-6">الفئات</h4>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer_categories',
                    'container'      => false,
                    'menu_class'     => 'space-y-3 text-gray-400',
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>
          </div>

          <!-- Bottom Bar -->
          <div
            class="border-t border-gray-700 pt-6 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-400"
          >
            <div class="flex gap-6 order-last md:order-first">
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer_bottom_links',
                    'container'      => false,
                    'menu_class'     => 'flex gap-6',
                    'fallback_cb'    => false,
                ) );
                ?>
            </div>
            <p class="max-md:text-center">
              <?php echo esc_html(get_field('footer_copyright', 'option')); ?>
            </p>
          </div>
        </div>
      </div>
    </footer>

<?php wp_footer(); ?>
</body>
</html>
