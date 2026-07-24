# El Nakaa native block migration

This release is deliberately deployment-safe:

- A `git pull` only installs the native block code and migration tools.
- It does not update the database.
- It does not deactivate or delete ACF.
- Legacy `acf/el-nakaa-*` blocks continue to render through ACF until an
  administrator explicitly runs the migration.

## Before deployment

1. Back up the production database and the installed ACF plugin directory.
2. Confirm WordPress 7.x and PHP 8.4 are still active.
3. Deploy the compatibility release and clear LiteSpeed/OPcache caches.
4. Verify the four published pages before running any migration.

## Dry run

In WordPress admin, open:

`Tools > El Nakaa Block Migration`

Run **Dry Run**. For the database snapshot exported on 2026-07-24, the expected
published-content result is:

- 4 changed pages
- 11 converted block instances
- all 9 El Nakaa block types represented

The report also checks whether Footer settings can be copied and counts products
that contain feature data.

WP-CLI alternative:

```sh
wp el-nakaa migrate-blocks
```

## Cutover

1. Briefly stop editors from changing pages/products.
2. Take a final database backup.
3. Run **Run Migration** in the Tools screen.
4. Clear LiteSpeed, object, and CDN caches.
5. Verify the homepage, products page, about page, contact page, footer, and
   product feature sections on desktop and mobile.
6. Open each migrated page in the block editor and confirm the native blocks
   load and can be edited.
7. Deactivate ACF, but do not delete it yet.
8. Repeat the frontend/editor verification.

WP-CLI write alternative:

```sh
wp el-nakaa migrate-blocks --write
```

The migration is idempotent. Before changing a post, it saves the original
content in `_el_nakaa_pre_native_blocks_content`.

## Rollback

The Tools screen contains **Restore Page Backups**, which restores page content
from the per-post backup. For a full rollback:

1. Restore the pre-cutover database backup.
2. Reactivate the saved ACF plugin version.
3. Deploy the previous theme release/tag.
4. Clear all caches.

Database restore is the authoritative rollback because it also restores Footer
options and product metadata.

## Final cleanup

Only after the site has run successfully without ACF:

- remove the legacy `acf-blocks.php` include;
- remove ACF JSON save/load filters;
- archive/remove `acf-json`;
- remove compatibility fallbacks from native content helpers;
- delete the old ACF plugin.
