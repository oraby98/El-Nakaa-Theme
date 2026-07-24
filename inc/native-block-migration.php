<?php
/**
 * Explicit, reversible migration from ACF blocks to native blocks.
 *
 * Nothing in this file runs a data migration during deployment. An administrator
 * must use Tools > El Nakaa Block Migration or the WP-CLI command.
 *
 * @package Bathe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Convert a media ID into the object consumed by the native editor/templates.
 *
 * @param mixed $value Legacy ACF value.
 * @return array
 */
function el_nakaa_migration_image_value( $value ) {
	$id = is_array( $value ) && isset( $value['id'] ) ? absint( $value['id'] ) : absint( $value );
	if ( ! $id ) {
		return array();
	}

	return array(
		'id'  => $id,
		'url' => wp_get_attachment_image_url( $id, 'full' ) ?: '',
		'alt' => get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
}

/**
 * Decode one ACF field from a serialized ACF block data array.
 *
 * @param array  $field  Field schema.
 * @param array  $data   ACF block data.
 * @param string $prefix Flattened repeater prefix.
 * @return mixed
 */
function el_nakaa_decode_acf_block_field( $field, $data, $prefix = '' ) {
	$name = $prefix . $field['name'];
	$type = isset( $field['type'] ) ? $field['type'] : 'text';

	if ( 'repeater' === $type ) {
		$count = isset( $data[ $name ] ) ? absint( $data[ $name ] ) : 0;
		$rows  = array();
		for ( $index = 0; $index < $count; $index++ ) {
			$row = array();
			foreach ( isset( $field['sub_fields'] ) ? $field['sub_fields'] : array() as $sub_field ) {
				if ( empty( $sub_field['name'] ) || 'tab' === $sub_field['type'] ) {
					continue;
				}
				$row[ $sub_field['name'] ] = el_nakaa_decode_acf_block_field(
					$sub_field,
					$data,
					$name . '_' . $index . '_'
				);
			}
			$rows[] = $row;
		}
		return $rows;
	}

	$value = isset( $data[ $name ] ) ? $data[ $name ] : null;
	if ( 'image' === $type ) {
		return el_nakaa_migration_image_value( $value );
	}
	if ( 'number' === $type || 'taxonomy' === $type ) {
		return absint( $value );
	}

	return null === $value ? '' : (string) $value;
}

/**
 * Convert ACF's data object into native attributes.
 *
 * @param string $legacy_slug Old slug without the acf/ namespace.
 * @param array  $data        ACF data object.
 * @return array
 */
function el_nakaa_decode_acf_block_data( $legacy_slug, $data ) {
	$attributes = array();
	foreach ( el_nakaa_native_block_fields( $legacy_slug ) as $field ) {
		if ( empty( $field['name'] ) || 'tab' === $field['type'] ) {
			continue;
		}
		$attributes[ $field['name'] ] = el_nakaa_decode_acf_block_field( $field, $data );
	}
	return $attributes;
}

/**
 * Recursively convert parsed blocks and return a conversion count.
 *
 * @param array $blocks Parsed blocks.
 * @param int   $count  Running conversion count.
 * @return array
 */
function el_nakaa_migrate_parsed_blocks( $blocks, &$count ) {
	$definitions = el_nakaa_native_block_definitions();

	foreach ( $blocks as &$block ) {
		if ( ! empty( $block['innerBlocks'] ) ) {
			$block['innerBlocks'] = el_nakaa_migrate_parsed_blocks( $block['innerBlocks'], $count );
		}

		if ( 0 !== strpos( (string) $block['blockName'], 'acf/' ) ) {
			continue;
		}

		$legacy_slug = substr( $block['blockName'], 4 );
		if ( ! isset( $definitions[ $legacy_slug ] ) ) {
			continue;
		}

		$native_slug        = substr( $legacy_slug, strlen( 'el-nakaa-' ) );
		$legacy_data        = isset( $block['attrs']['data'] ) && is_array( $block['attrs']['data'] ) ? $block['attrs']['data'] : array();
		$native_attributes  = el_nakaa_decode_acf_block_data( $legacy_slug, $legacy_data );
		$block['blockName'] = 'el-nakaa/' . $native_slug;
		$block['attrs']     = $native_attributes;
		$block['innerHTML'] = '';
		$block['innerContent'] = array();
		$count++;
	}
	unset( $block );

	return $blocks;
}

/**
 * Convert one content string without writing it.
 *
 * @param string $content Post content.
 * @return array{content:string,count:int}
 */
function el_nakaa_migrate_content_string( $content ) {
	$count  = 0;
	$blocks = el_nakaa_migrate_parsed_blocks( parse_blocks( $content ), $count );

	return array(
		'content' => $count ? serialize_blocks( $blocks ) : $content,
		'count'   => $count,
	);
}

/**
 * Find current content records containing legacy blocks.
 *
 * @return WP_Post[]
 */
function el_nakaa_legacy_block_posts() {
	return get_posts(
		array(
			'post_type'        => 'any',
			'post_status'      => array( 'publish', 'private', 'draft', 'pending', 'future' ),
			'posts_per_page'   => -1,
			'suppress_filters' => false,
			's'                => 'wp:acf/el-nakaa-',
			'orderby'          => 'ID',
			'order'            => 'ASC',
		)
	);
}

/**
 * Run or preview the page-content migration.
 *
 * @param bool $write Whether to update the database.
 * @return array
 */
function el_nakaa_run_block_migration( $write = false ) {
	$report = array(
		'mode'             => $write ? 'write' : 'dry-run',
		'posts_scanned'    => 0,
		'posts_changed'    => 0,
		'blocks_converted' => 0,
		'errors'           => array(),
		'items'            => array(),
		'footer_ready'     => false,
		'products_scanned' => 0,
		'products_changed' => 0,
	);

	foreach ( el_nakaa_legacy_block_posts() as $post ) {
		$report['posts_scanned']++;
		$result = el_nakaa_migrate_content_string( $post->post_content );
		if ( ! $result['count'] ) {
			continue;
		}

		$item = array(
			'id'     => $post->ID,
			'title'  => get_the_title( $post ),
			'type'   => $post->post_type,
			'status' => $post->post_status,
			'blocks' => $result['count'],
		);

		if ( $write ) {
			$backup_key = '_el_nakaa_pre_native_blocks_content';
			if ( ! metadata_exists( 'post', $post->ID, $backup_key ) ) {
				update_post_meta( $post->ID, $backup_key, $post->post_content );
			}

			$updated = wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_content' => $result['content'],
				),
				true
			);
			if ( is_wp_error( $updated ) ) {
				$report['errors'][] = 'Post ' . $post->ID . ': ' . $updated->get_error_message();
				continue;
			}
		}

		$report['posts_changed']++;
		$report['blocks_converted'] += $result['count'];
		$report['items'][] = $item;
	}

	$site_data                  = el_nakaa_migrate_site_data( $write );
	$report['footer_ready']     = $site_data['footer_ready'];
	$report['products_scanned'] = $site_data['products_scanned'];
	$report['products_changed'] = $site_data['products_changed'];
	$report['errors']           = array_merge( $report['errors'], $site_data['errors'] );

	return $report;
}

/**
 * Restore page content from the per-post migration backups.
 *
 * @return array
 */
function el_nakaa_restore_block_migration() {
	$report = array( 'restored' => 0, 'errors' => array() );
	$posts  = get_posts(
		array(
			'post_type'      => 'any',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'meta_key'       => '_el_nakaa_pre_native_blocks_content',
			'fields'         => 'ids',
		)
	);

	foreach ( $posts as $post_id ) {
		$content = get_post_meta( $post_id, '_el_nakaa_pre_native_blocks_content', true );
		$result  = wp_update_post( array( 'ID' => $post_id, 'post_content' => $content ), true );
		if ( is_wp_error( $result ) ) {
			$report['errors'][] = 'Post ' . $post_id . ': ' . $result->get_error_message();
			continue;
		}
		$report['restored']++;
	}

	return $report;
}

/**
 * Preview or migrate Footer options and WooCommerce product features.
 *
 * @param bool $write Whether to update native storage.
 * @return array
 */
function el_nakaa_migrate_site_data( $write = false ) {
	$report = array(
		'footer_ready'     => false,
		'products_scanned' => 0,
		'products_changed' => 0,
		'errors'           => array(),
	);

	$footer = el_nakaa_footer_settings();
	if ( $footer ) {
		$report['footer_ready'] = true;
		if ( $write && ! update_option( 'el_nakaa_footer_settings', $footer, false ) ) {
			$stored = get_option( 'el_nakaa_footer_settings', array() );
			if ( $stored !== $footer ) {
				$report['errors'][] = 'Footer settings could not be saved.';
			}
		}
	}

	if ( ! post_type_exists( 'product' ) ) {
		return $report;
	}

	$product_ids = get_posts(
		array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'private', 'draft', 'pending', 'future' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	foreach ( $product_ids as $product_id ) {
		$report['products_scanned']++;
		$features = el_nakaa_product_features( $product_id );
		if ( ! $features ) {
			continue;
		}

		$report['products_changed']++;
		if ( $write ) {
			update_post_meta( $product_id, '_el_nakaa_product_features', $features );
		}
	}

	return $report;
}

/**
 * Register the migration screen.
 */
function el_nakaa_register_migration_page() {
	add_management_page(
		'El Nakaa Block Migration',
		'El Nakaa Block Migration',
		'manage_options',
		'el-nakaa-block-migration',
		'el_nakaa_render_migration_page'
	);
}
add_action( 'admin_menu', 'el_nakaa_register_migration_page' );

/**
 * Render the explicit migration UI.
 */
function el_nakaa_render_migration_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You are not allowed to access this page.', 'bathe' ) );
	}

	$report = null;
	$restore_report = null;
	if ( isset( $_POST['el_nakaa_migration_action'] ) ) {
		check_admin_referer( 'el_nakaa_native_block_migration' );
		$action = sanitize_key( wp_unslash( $_POST['el_nakaa_migration_action'] ) );
		if ( 'restore' === $action ) {
			$restore_report = el_nakaa_restore_block_migration();
		} else {
			$report = el_nakaa_run_block_migration( 'run' === $action );
		}
	}
	?>
	<div class="wrap">
		<h1>El Nakaa Native Block Migration</h1>
		<p><strong>Dry Run</strong> لا يكتب أي بيانات. التشغيل الحقيقي يحفظ نسخة من محتوى كل صفحة في post meta قبل التحويل.</p>
		<form method="post">
			<?php wp_nonce_field( 'el_nakaa_native_block_migration' ); ?>
			<button class="button button-secondary" name="el_nakaa_migration_action" value="dry-run">Dry Run</button>
			<button class="button button-primary" name="el_nakaa_migration_action" value="run" onclick="return confirm('تم أخذ نسخة احتياطية حديثة. هل تريد تنفيذ التحويل الآن؟');">Run Migration</button>
			<button class="button" name="el_nakaa_migration_action" value="restore" onclick="return confirm('سيتم استرجاع محتوى الصفحات المحفوظ قبل التحويل. هل تريد المتابعة؟');">Restore Page Backups</button>
		</form>
		<?php if ( is_array( $restore_report ) ) : ?>
			<div class="notice notice-info"><p>Restored posts: <?php echo esc_html( $restore_report['restored'] ); ?></p></div>
			<?php foreach ( $restore_report['errors'] as $error ) : ?><div class="notice notice-error"><p><?php echo esc_html( $error ); ?></p></div><?php endforeach; ?>
		<?php endif; ?>
		<?php if ( is_array( $report ) ) : ?>
			<h2>Report: <?php echo esc_html( $report['mode'] ); ?></h2>
			<p>Posts scanned: <?php echo esc_html( $report['posts_scanned'] ); ?> | Posts changed: <?php echo esc_html( $report['posts_changed'] ); ?> | Blocks: <?php echo esc_html( $report['blocks_converted'] ); ?></p>
			<p>Footer ready: <?php echo $report['footer_ready'] ? 'yes' : 'no'; ?> | Products scanned: <?php echo esc_html( $report['products_scanned'] ); ?> | Products with features: <?php echo esc_html( $report['products_changed'] ); ?></p>
			<table class="widefat striped">
				<thead><tr><th>ID</th><th>Title</th><th>Type</th><th>Status</th><th>Blocks</th></tr></thead>
				<tbody>
				<?php foreach ( $report['items'] as $item ) : ?>
					<tr><td><?php echo esc_html( $item['id'] ); ?></td><td><?php echo esc_html( $item['title'] ); ?></td><td><?php echo esc_html( $item['type'] ); ?></td><td><?php echo esc_html( $item['status'] ); ?></td><td><?php echo esc_html( $item['blocks'] ); ?></td></tr>
				<?php endforeach; ?>
				</tbody>
			</table>
			<?php foreach ( $report['errors'] as $error ) : ?>
				<div class="notice notice-error"><p><?php echo esc_html( $error ); ?></p></div>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<?php
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command(
		'el-nakaa migrate-blocks',
		static function ( $args, $assoc_args ) {
			$write  = isset( $assoc_args['write'] );
			$report = el_nakaa_run_block_migration( $write );
			WP_CLI::log( wp_json_encode( $report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) );
			if ( $report['errors'] ) {
				WP_CLI::error( 'Migration finished with errors.' );
			}
			WP_CLI::success( $write ? 'Migration completed.' : 'Dry run completed; no data was changed.' );
		}
	);
}
