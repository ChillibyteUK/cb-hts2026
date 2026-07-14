# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

`cb-hts2026` is a WordPress child theme built on the Understrap (Bootstrap 5)
framework. Content is authored as custom ACF Gutenberg blocks; styling is
Sass compiled to a single bundled stylesheet; there is no PHP or JS test
suite — correctness is checked via linters/static analysis and manual
verification in the browser.

## Commands

Install JS deps first (`npm install`) — a `postinstall` hook patches
`node_modules/understrap`'s `phpcs.xml.dist` out of the way so it doesn't
conflict with this theme's own PHPCS config.

Build:
- `npm run css` — compile Sass → PostCSS (autoprefixer) → minify. Source of
  truth is `src/sass/child-theme.scss`; output is `css/child-theme.css` +
  `.min.css`. Run this after any `.scss` edit — nothing watches by default.
- `npm run js` — rollup bundle → terser minify, `src/js/*` → `js/child-theme.min.js`.
- `npm run dist` — runs `css` and `js` in parallel.
- `npm run watch` / `npm run watch-bs` — nodemon watchers for `src/js` and
  `src/sass` (the `-bs` variant also starts BrowserSync).
- `npm run generate-theme-json` — regenerates `theme.json` from
  `src/sass/theme/_tokens.scss`. There's also a dedicated watcher,
  `watch-run-theme-json`, that runs this automatically on token changes.
- `npm run format` — prettier over JS/SCSS/JSON/MD (`format:js`, `format:scss`,
  `format:json`, `format:md` individually).

Lint/static analysis (PHP):
- `npm run lint:php` / `npm run fix:php` (phpcs / phpcbf wrappers), or via
  Composer: `composer phpcs`, `composer phpcs-fix`, `composer phpstan`,
  `composer phpmd`, `composer php-lint` (parallel-lint syntax check).
  Baselines exist at `phpstan-baseline.neon` and `phpmd.baseline.xml` — new
  code should not need entries added there.

Never hand-edit `theme.json`'s color/typography palette — it's a generated
artifact of `_tokens.scss` (see Architecture below); edits will be
overwritten.

## Architecture

**Block system.** Every custom block is four files kept in lockstep:
1. `blocks/cb-<name>.php` — render template (receives ACF fields via `get_field()`).
2. `src/sass/theme/blocks/_cb_<name>.scss` — block styles, imported by
   `src/sass/theme/blocks/_blocks.scss`.
3. `acf-json/group_cb_<name>.json` — ACF field group definition. ACF is
   configured (in `inc/cb-blocks.php`) to save/load field groups from
   `acf-json/` instead of the DB, so this file is the actual source of
   truth for a block's fields.
4. A registration call in `inc/cb-blocks.php`'s `acf_blocks()`, added at the
   `// INSERT NEW BLOCKS HERE.` marker.

Use the scaffolding scripts rather than hand-rolling all four:
- `./add_block.sh [-c] <name>` — creates all four pieces and wires up the
  registration + SCSS import. `-c` adds Gutenberg color-picker support
  (background/text) to the block's `supports`.
- `./rm_block.sh` — interactive; removes all four pieces for a chosen block.
- `./cleanup_blocks.sh` — finds block registrations in `cb-blocks.php` that
  have no matching `acf-json/group_cb_*.json` (orphaned) and offers to
  remove them.
- `./populate_acf_from_block.sh blocks/cb-<name>.php` — infers an ACF field
  schema from a block's `get_field()` calls and (re)writes its
  `acf-json/group_cb_<name>.json`. A render template can override inferred
  field types/options/order via an
  `ACF_FIELDS_START ... ACF_FIELDS_END` manifest comment block — see the
  script header for the pipe-delimited format.

**Design tokens → theme.json.** `src/sass/theme/_tokens.scss` defines every
CSS custom property (`--col-*` colors, `--fs-*` font sizes, `--fw-*`
weights, etc.) inside a single `:root` block. `generate-theme-json.js`
parses that block and regenerates `theme.json`'s color palette (from
`--col-*`) and font-size scale (from `--fs-*`) — it is the only writer of
those sections. Add new palette/type-scale entries in `_tokens.scss`, then
regenerate; don't add them to `theme.json` directly.

**CSS/JS enqueue.** `functions.php` dequeues Understrap's parent styles/
scripts and WP's block-library CSS, then enqueues only the theme's own
minified bundles (`css/child-theme.min.css`, `js/child-theme.min.js`) with
`filemtime()`-based cache busting. Because of this, edits to `css/` or `js/`
source files have no effect on the live site until the corresponding build
command has been run.

**Core block wrapping.** `register_block_type_args` (in `inc/cb-blocks.php`)
overrides the render callback for `core/paragraph`, `core/heading`,
`core/list`, and `core/separator` to wrap their output in `<div
class="container">`, except when rendering is detected (via
`debug_backtrace()`) to be happening inside `footer.php`.

**`inc/` load order**, from `functions.php`: `inc/cb-theme.php` and
`inc/cb-block-usage.php` are required directly; `cb-theme.php` in turn
requires `cb-utility.php`, `cb-acf-theme-palette.php`, `cb-posttypes.php`,
`cb-taxonomies.php`, then `cb-blocks.php`.

**Block usage audit.** The `[block_usage_table]` shortcode
(`inc/cb-block-usage.php`) scans all published pages/posts for
`<!-- wp:acf/cb-* -->` block comments and renders a table of which blocks
are used where — check this (or grep post content directly) before
removing a block that might still be placed on a page.
