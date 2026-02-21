<?php
/**
 * The header
 *
 * @package Bathe
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap"
      rel="stylesheet"
    />
<?php wp_head(); ?>
</head>

<body <?php body_class( 'antialiased flex flex-col min-h-screen' ); ?>>
<?php wp_body_open(); ?>

    <!-- Top Bar -->
    <nav>
      <!-- Mobile Menu Script -->
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const menuBtn = document.getElementById('menu-btn');
          const mobileMenu = document.getElementById('mobile-menu');

          if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
              mobileMenu.classList.toggle('hidden');
            });
          }
        });
      </script>

      <div class="bg-secColor text-white py-4">
        <div
          class="container mx-auto px-4 max-md:flex-col flex gap-2 justify-between items-center text-sm"
        >
          <div class="flex items-center gap-2">
            <span>اتصل بنا</span>
            <i class="fa-solid fa-phone text-mainColor"></i>
            <span dir="ltr">0123456789</span>
          </div>
          <p class="font-medium">شحن مجاني للطلبات فوق 1000 جنيه</p>
        </div>
      </div>

      <!-- Main Header -->
      <div class="bg-white sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4">
          <div class="flex justify-between items-center py-4 gap-4">
            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block">
              <?php
              $custom_logo_id = get_theme_mod( 'custom_logo' );
              $logo_src = $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : get_theme_file_uri( 'assets/images/logo.png' );
              ?>
              <img
                src="<?php echo esc_url( $logo_src ); ?>"
                alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                class="w-16 md:w-18 object-contain"
              />
            </a>

            <!-- Search Bar (Hidden on Mobile, visible on lg) -->
            <div class="hidden lg:block flex-1 max-w-2xl px-8">
              <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="relative">
                <input
                  type="text"
                  name="s"
                  placeholder="ابحث عن المنتجات..."
                  class="w-full border border-gray-200 rounded-lg py-3 px-4 text-start outline-none focus:border-mainColor focus:ring-1 focus:ring-mainColor transition-all font-zain placeholder:text-gray-400 bg-gray-50"
                  value="<?php echo get_search_query(); ?>"
                />
                <button
                  type="submit"
                  class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-mainColor transition-colors"
                  title="بحث"
                >
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </form>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 md:gap-6">
              <a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" class="relative cursor-pointer group">
                <i
                  class="fa-regular fa-heart text-2xl text-gray-700 group-hover:text-red-500 transition-colors"
                ></i>
                <span
                  class="absolute -top-2 -start-2 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white"
                  ><?php echo function_exists( 'yith_wcwl_count_all_products' ) ? yith_wcwl_count_all_products() : 0; ?></span>
              </a>

              <a href="<?php echo esc_url( home_url( '/cart/' ) ); ?>" class="relative cursor-pointer group">
                <i
                  class="fa-solid fa-cart-shopping text-2xl text-gray-700 group-hover:text-secColor transition-colors"
                ></i>
                <span
                  class="absolute -top-2 -start-2 bg-secColor text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white"
                  ><?php echo count( WC()->cart->get_cart() ); ?></span>
              </a>

              <!-- Hamburger Button -->
              <button
                id="menu-btn"
                class="lg:hidden text-2xl text-gray-700 focus:outline-none ml-2"
              >
                <i class="fa-solid fa-bars"></i>
              </button>
            </div>
          </div>

          <!-- Desktop Navigation -->
          <nav class="hidden lg:block border-t border-gray-100">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex justify-center items-center gap-8 py-4 font-bold text-secColor',
                'fallback_cb'    => false,
            ) );
            ?>
          </nav>
        </div>

        <!-- Mobile Menu (Dropdown) -->
        <div
          id="mobile-menu"
          class="hidden lg:hidden border-t border-gray-100 bg-white absolute w-full left-0 top-full shadow-lg"
        >
          <ul class="flex flex-col p-4 gap-4 font-bold text-secColor">
            <!-- Mobile Search -->
            <li class="pb-2 border-b border-gray-100">
              <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="relative mt-2">
                <input
                  type="text"
                  name="s"
                  placeholder="ابحث عن المنتجات..."
                  class="w-full border border-gray-200 rounded-lg py-2.5 px-4 text-start outline-none focus:border-mainColor focus:ring-1 focus:ring-mainColor bg-gray-50 text-sm"
                   value="<?php echo get_search_query(); ?>"
                />
                <button
                  type="submit"
                  class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                >
                  <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </form>
            </li>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s', // Removes the <ul> wrap since we are inside a <ul>
                'fallback_cb'    => false,
            ) );
            ?>
          </ul>
        </div>
      </div>
    </nav>
