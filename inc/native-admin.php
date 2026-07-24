<?php
/**
 * Native admin screens used after ACF is removed.
 *
 * @package Bathe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a list of associative rows.
 *
 * @param mixed $rows       Submitted rows.
 * @param array $text_keys  Plain text keys.
 * @param array $url_keys   URL keys.
 * @param array $html_keys  HTML/textarea keys.
 * @return array
 */
function el_nakaa_sanitize_rows( $rows, $text_keys, $url_keys = array(), $html_keys = array() ) {
	$clean = array();
	if ( ! is_array( $rows ) ) {
		return $clean;
	}

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$item = array();
		foreach ( $text_keys as $key ) {
			$item[ $key ] = isset( $row[ $key ] ) ? sanitize_text_field( wp_unslash( $row[ $key ] ) ) : '';
		}
		foreach ( $url_keys as $key ) {
			$item[ $key ] = isset( $row[ $key ] ) ? esc_url_raw( wp_unslash( $row[ $key ] ) ) : '';
		}
		foreach ( $html_keys as $key ) {
			$item[ $key ] = isset( $row[ $key ] ) ? wp_kses_post( wp_unslash( $row[ $key ] ) ) : '';
		}
		$clean[] = $item;
	}
	return $clean;
}

/**
 * Add the native footer settings screen.
 */
function el_nakaa_native_admin_menu() {
	add_theme_page(
		'Native Footer Settings',
		'Native Footer Settings',
		'edit_theme_options',
		'el-nakaa-native-footer',
		'el_nakaa_render_native_footer_page'
	);
}
add_action( 'admin_menu', 'el_nakaa_native_admin_menu' );

/**
 * Save native footer settings.
 */
function el_nakaa_save_native_footer() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to perform this action.', 'bathe' ) );
	}
	check_admin_referer( 'el_nakaa_save_native_footer' );

	$input = isset( $_POST['footer'] ) && is_array( $_POST['footer'] ) ? $_POST['footer'] : array();
	$data  = array(
		'footer_features'   => el_nakaa_sanitize_rows( isset( $input['footer_features'] ) ? $input['footer_features'] : array(), array( 'icon_class', 'title', 'subtitle' ) ),
		'footer_logo'       => isset( $input['footer_logo'] ) ? esc_url_raw( wp_unslash( $input['footer_logo'] ) ) : '',
		'footer_about_text' => isset( $input['footer_about_text'] ) ? sanitize_textarea_field( wp_unslash( $input['footer_about_text'] ) ) : '',
		'footer_socials'    => el_nakaa_sanitize_rows( isset( $input['footer_socials'] ) ? $input['footer_socials'] : array(), array( 'icon_class' ), array( 'url' ) ),
		'footer_address'    => isset( $input['footer_address'] ) ? sanitize_text_field( wp_unslash( $input['footer_address'] ) ) : '',
		'footer_phone'      => isset( $input['footer_phone'] ) ? sanitize_text_field( wp_unslash( $input['footer_phone'] ) ) : '',
		'footer_email'      => isset( $input['footer_email'] ) ? sanitize_email( wp_unslash( $input['footer_email'] ) ) : '',
		'footer_copyright'  => isset( $input['footer_copyright'] ) ? sanitize_text_field( wp_unslash( $input['footer_copyright'] ) ) : '',
	);

	update_option( 'el_nakaa_footer_settings', $data, false );
	wp_safe_redirect( add_query_arg( array( 'page' => 'el-nakaa-native-footer', 'updated' => '1' ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_el_nakaa_save_native_footer', 'el_nakaa_save_native_footer' );

/**
 * Render a repeatable footer table.
 *
 * @param string $key     Setting key.
 * @param array  $rows    Existing rows.
 * @param array  $columns Input key => label.
 */
function el_nakaa_footer_repeater_table( $key, $rows, $columns ) {
	?>
	<table class="widefat striped el-nakaa-repeater" data-key="<?php echo esc_attr( $key ); ?>">
		<thead><tr><?php foreach ( $columns as $label ) : ?><th><?php echo esc_html( $label ); ?></th><?php endforeach; ?><th></th></tr></thead>
		<tbody>
		<?php foreach ( $rows as $index => $row ) : ?>
			<tr>
			<?php foreach ( $columns as $column => $label ) : ?>
				<td><input class="widefat" name="footer[<?php echo esc_attr( $key ); ?>][<?php echo esc_attr( $index ); ?>][<?php echo esc_attr( $column ); ?>]" value="<?php echo esc_attr( isset( $row[ $column ] ) ? $row[ $column ] : '' ); ?>"></td>
			<?php endforeach; ?>
				<td><button type="button" class="button-link-delete el-nakaa-remove-row">حذف</button></td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<p><button type="button" class="button el-nakaa-add-row" data-columns="<?php echo esc_attr( implode( ',', array_keys( $columns ) ) ); ?>" data-key="<?php echo esc_attr( $key ); ?>">إضافة عنصر</button></p>
	<?php
}

/**
 * Render native footer settings.
 */
function el_nakaa_render_native_footer_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$settings = el_nakaa_footer_settings();
	?>
	<div class="wrap">
		<h1>Native Footer Settings</h1>
		<?php if ( isset( $_GET['updated'] ) ) : ?><div class="notice notice-success"><p>تم حفظ الإعدادات.</p></div><?php endif; ?>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<input type="hidden" name="action" value="el_nakaa_save_native_footer">
			<?php wp_nonce_field( 'el_nakaa_save_native_footer' ); ?>
			<h2>Features</h2>
			<?php el_nakaa_footer_repeater_table( 'footer_features', isset( $settings['footer_features'] ) ? $settings['footer_features'] : array(), array( 'icon_class' => 'Icon Class', 'title' => 'Title', 'subtitle' => 'Subtitle' ) ); ?>
			<h2>About</h2>
			<p><label>Logo URL<br><input class="regular-text" type="url" name="footer[footer_logo]" value="<?php echo esc_attr( el_nakaa_image_url( isset( $settings['footer_logo'] ) ? $settings['footer_logo'] : '' ) ); ?>"></label></p>
			<p><label>About Text<br><textarea class="large-text" rows="4" name="footer[footer_about_text]"><?php echo esc_textarea( isset( $settings['footer_about_text'] ) ? $settings['footer_about_text'] : '' ); ?></textarea></label></p>
			<h2>Social Links</h2>
			<?php el_nakaa_footer_repeater_table( 'footer_socials', isset( $settings['footer_socials'] ) ? $settings['footer_socials'] : array(), array( 'icon_class' => 'Icon Class', 'url' => 'URL' ) ); ?>
			<h2>Contact and Copyright</h2>
			<?php foreach ( array( 'footer_address' => 'Address', 'footer_phone' => 'Phone', 'footer_email' => 'Email', 'footer_copyright' => 'Copyright' ) as $key => $label ) : ?>
				<p><label><?php echo esc_html( $label ); ?><br><input class="regular-text" name="footer[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( isset( $settings[ $key ] ) ? $settings[ $key ] : '' ); ?>"></label></p>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<script>
	document.addEventListener('click', function(event) {
		if (event.target.classList.contains('el-nakaa-remove-row')) {
			event.target.closest('tr').remove();
		}
		if (event.target.classList.contains('el-nakaa-add-row')) {
			const button = event.target;
			const table = button.parentElement.previousElementSibling;
			const index = table.querySelectorAll('tbody tr').length;
			const columns = button.dataset.columns.split(',');
			const row = document.createElement('tr');
			row.innerHTML = columns.map(function(column) {
				return '<td><input class="widefat" name="footer[' + button.dataset.key + '][' + index + '][' + column + ']" value=""></td>';
			}).join('') + '<td><button type="button" class="button-link-delete el-nakaa-remove-row">حذف</button></td>';
			table.querySelector('tbody').appendChild(row);
		}
	});
	</script>
	<?php
}

/**
 * Register the product features meta box only after ACF is unavailable.
 */
function el_nakaa_native_product_meta_box() {
	if ( function_exists( 'acf' ) || function_exists( 'get_field' ) ) {
		return;
	}
	add_meta_box( 'el-nakaa-product-features', 'Product Features', 'el_nakaa_render_product_features_meta_box', 'product', 'normal', 'default' );
}
add_action( 'add_meta_boxes', 'el_nakaa_native_product_meta_box' );

/**
 * Render a native repeatable Product Features editor.
 *
 * @param WP_Post $post Current product.
 */
function el_nakaa_render_product_features_meta_box( $post ) {
	wp_nonce_field( 'el_nakaa_save_product_features', 'el_nakaa_product_features_nonce' );
	$features = el_nakaa_product_features( $post->ID );
	wp_enqueue_media();
	?>
	<div id="el-nakaa-product-features">
		<?php foreach ( $features as $index => $feature ) : ?>
			<?php el_nakaa_render_product_feature_row( $index, $feature ); ?>
		<?php endforeach; ?>
	</div>
	<p><button type="button" class="button" id="el-nakaa-add-feature">إضافة ميزة</button></p>
	<script type="text/html" id="tmpl-el-nakaa-product-feature">
		<?php el_nakaa_render_product_feature_row( '__INDEX__', array() ); ?>
	</script>
	<script>
	( function() {
		const container = document.getElementById('el-nakaa-product-features');
		const template = document.getElementById('tmpl-el-nakaa-product-feature').innerHTML;

		document.getElementById('el-nakaa-add-feature').addEventListener('click', function() {
			const wrapper = document.createElement('div');
			wrapper.innerHTML = template.replaceAll('__INDEX__', Date.now().toString());
			container.appendChild(wrapper.firstElementChild);
		});

		document.addEventListener('click', function(event) {
			if (event.target.classList.contains('el-nakaa-remove-feature')) {
				event.target.closest('.el-nakaa-product-feature').remove();
			}
			if (event.target.classList.contains('el-nakaa-remove-spec')) {
				event.target.closest('.el-nakaa-product-spec').remove();
			}
			if (event.target.classList.contains('el-nakaa-add-spec')) {
				const feature = event.target.closest('.el-nakaa-product-feature');
				const specs = feature.querySelector('.el-nakaa-product-specs');
				const featureIndex = feature.dataset.index;
				const specIndex = Date.now().toString();
				const row = document.createElement('p');
				row.className = 'el-nakaa-product-spec';
				row.innerHTML = '<input class="widefat" name="el_nakaa_product_features[' + featureIndex + '][specs][' + specIndex + '][spec_text]" value=""> <button type="button" class="button-link-delete el-nakaa-remove-spec">حذف</button>';
				specs.appendChild(row);
			}
			if (event.target.classList.contains('el-nakaa-select-feature-image')) {
				const feature = event.target.closest('.el-nakaa-product-feature');
				const frame = wp.media({ title: 'اختيار صورة الميزة', button: { text: 'استخدام الصورة' }, multiple: false });
				frame.on('select', function() {
					const image = frame.state().get('selection').first().toJSON();
					feature.querySelector('.el-nakaa-feature-image-id').value = image.id;
					feature.querySelector('.el-nakaa-feature-image-url').value = image.url;
					feature.querySelector('.el-nakaa-feature-image-preview').src = image.url;
					feature.querySelector('.el-nakaa-feature-image-preview').hidden = false;
				});
				frame.open();
			}
		});
	}() );
	</script>
	<?php
}

/**
 * Render one Product Feature row.
 *
 * @param int|string $index   Row index.
 * @param array      $feature Existing feature.
 */
function el_nakaa_render_product_feature_row( $index, $feature ) {
	$title       = isset( $feature['title'] ) ? $feature['title'] : '';
	$description = isset( $feature['description'] ) ? $feature['description'] : '';
	$image       = isset( $feature['image'] ) ? $feature['image'] : array();
	$image_id    = is_array( $image ) && isset( $image['id'] ) ? $image['id'] : ( is_array( $image ) && isset( $image['ID'] ) ? $image['ID'] : 0 );
	$image_url   = el_nakaa_image_url( $image );
	$specs       = isset( $feature['specs'] ) && is_array( $feature['specs'] ) ? $feature['specs'] : array();
	$background  = isset( $feature['background_style'] ) ? $feature['background_style'] : '';
	$name        = 'el_nakaa_product_features[' . $index . ']';
	?>
	<div class="el-nakaa-product-feature" data-index="<?php echo esc_attr( $index ); ?>" style="border:1px solid #ccd0d4;padding:16px;margin:0 0 16px;background:#fff">
		<p><label>العنوان<br><input class="widefat" name="<?php echo esc_attr( $name ); ?>[title]" value="<?php echo esc_attr( $title ); ?>"></label></p>
		<p><label>الوصف<br><textarea class="widefat" rows="4" name="<?php echo esc_attr( $name ); ?>[description]"><?php echo esc_textarea( $description ); ?></textarea></label></p>
		<p>
			<img class="el-nakaa-feature-image-preview" src="<?php echo esc_url( $image_url ); ?>" style="max-width:180px;height:auto" <?php echo $image_url ? '' : 'hidden'; ?>>
			<input class="el-nakaa-feature-image-id" type="hidden" name="<?php echo esc_attr( $name ); ?>[image][id]" value="<?php echo esc_attr( $image_id ); ?>">
			<input class="el-nakaa-feature-image-url" type="hidden" name="<?php echo esc_attr( $name ); ?>[image][url]" value="<?php echo esc_attr( $image_url ); ?>">
			<button type="button" class="button el-nakaa-select-feature-image">اختيار/تغيير الصورة</button>
		</p>
		<p><label>Background Style<br><input class="widefat" name="<?php echo esc_attr( $name ); ?>[background_style]" value="<?php echo esc_attr( $background ); ?>"></label></p>
		<h4>المواصفات التقنية</h4>
		<div class="el-nakaa-product-specs">
			<?php foreach ( $specs as $spec_index => $spec ) : ?>
				<p class="el-nakaa-product-spec"><input class="widefat" name="<?php echo esc_attr( $name ); ?>[specs][<?php echo esc_attr( $spec_index ); ?>][spec_text]" value="<?php echo esc_attr( isset( $spec['spec_text'] ) ? $spec['spec_text'] : '' ); ?>"> <button type="button" class="button-link-delete el-nakaa-remove-spec">حذف</button></p>
			<?php endforeach; ?>
		</div>
		<p><button type="button" class="button el-nakaa-add-spec">إضافة مواصفة</button></p>
		<p><button type="button" class="button-link-delete el-nakaa-remove-feature">حذف الميزة</button></p>
	</div>
	<?php
}

/**
 * Save native product feature data.
 *
 * @param int $post_id Product ID.
 */
function el_nakaa_save_product_features( $post_id ) {
	if ( ! isset( $_POST['el_nakaa_product_features_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['el_nakaa_product_features_nonce'] ) ), 'el_nakaa_save_product_features' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$submitted = isset( $_POST['el_nakaa_product_features'] ) && is_array( $_POST['el_nakaa_product_features'] )
		? $_POST['el_nakaa_product_features']
		: array();
	$features  = array();

	foreach ( $submitted as $feature ) {
		if ( ! is_array( $feature ) ) {
			continue;
		}
		$image = isset( $feature['image'] ) && is_array( $feature['image'] ) ? $feature['image'] : array();
		$specs = array();
		foreach ( isset( $feature['specs'] ) && is_array( $feature['specs'] ) ? $feature['specs'] : array() as $spec ) {
			$text = isset( $spec['spec_text'] ) ? sanitize_text_field( wp_unslash( $spec['spec_text'] ) ) : '';
			if ( '' !== $text ) {
				$specs[] = array( 'spec_text' => $text );
			}
		}

		$features[] = array(
			'title'            => isset( $feature['title'] ) ? sanitize_text_field( wp_unslash( $feature['title'] ) ) : '',
			'description'      => isset( $feature['description'] ) ? sanitize_textarea_field( wp_unslash( $feature['description'] ) ) : '',
			'image'            => array(
				'id'  => isset( $image['id'] ) ? absint( $image['id'] ) : 0,
				'url' => isset( $image['url'] ) ? esc_url_raw( wp_unslash( $image['url'] ) ) : '',
			),
			'specs'            => $specs,
			'background_style' => isset( $feature['background_style'] ) ? sanitize_text_field( wp_unslash( $feature['background_style'] ) ) : '',
		);
	}

	update_post_meta( $post_id, '_el_nakaa_product_features', $features );
}
add_action( 'save_post_product', 'el_nakaa_save_product_features' );
