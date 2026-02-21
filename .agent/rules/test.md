---
trigger: always_on
---

cycle of create block

first open @acf-blocks.php and define block

then in folder acf-json create ACF custom field group that can control the block from it

then in the defined file related to block in @template-parts/blocks add the html code that given to you as it is whiout in cahnges in classes or styles or strucute just add php code to in and make it dynamic and remove data-i18n and don't leave static images like this <img src="<?php echo get_theme_file_uri('/assets/images/bg-vector-2.png'); ?>" alt="" class="max-h-20 object-contain ltr:rotate-180" />
make images dynamic and add input in block

don't use this behavior
<style>
@media (min-width: 1024px) { #<?php echo esc_attr($id); ?> {
background-image: url('<?php echo esc_url($bg_image['url']); ?>'), linear-gradient(269deg,#f2f2f2_36.42%,#a6a49f_99.55%);
}
}
</style>

use the same behavior that provide to you in tailwind and html strucuture
