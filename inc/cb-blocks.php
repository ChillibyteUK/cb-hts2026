<?php
/**
 * File responsible for registering custom ACF blocks and modifying core block arguments.
 *
 * @package cb-hts2026
 */

/**
 * Opt every ACF block into block API v3.
 *
 * Block API v3 unlocks ACF's expanded editor and toolbar fields, so wysiwyg
 * fields get a wide panel with a real TinyMCE instead of being crammed into
 * the inspector — the toolbar/expanded-editor code path in ACF returns early
 * for anything below 3. Blocks then render as previews in the canvas.
 */
add_filter( 'acf/blocks/default_block_version', fn() => 3, 99 );

/**
 * Let ACF preview blocks in the canvas rather than forcing edit forms into it.
 *
 * The cbp-blog-options plugin pins ACF blocks to edit mode, which predates WP 7's
 * iframed canvas — inside the iframe those forms lose TinyMCE entirely. v3's
 * expanded editor replaces that arrangement, so opt this theme out.
 */
add_filter( 'cbp_acf_blocks_force_edit_mode', '__return_false' );

/**
 * Put the front-end stylesheet in the editor canvas.
 *
 * Blocks now preview rather than showing their edit form, so the canvas is
 * rendering real block markup — without the theme's CSS it reads as unstyled
 * text. Styles enqueued here reach the canvas iframe.
 */
add_action(
	'enqueue_block_assets',
	function () {
		if ( ! is_admin() ) {
			return;
		}

		$css = get_stylesheet_directory() . '/css/child-theme.min.css';

		if ( ! file_exists( $css ) ) {
			return;
		}

		wp_enqueue_style(
			'cb-child-theme-editor',
			get_stylesheet_directory_uri() . '/css/child-theme.min.css',
			array(),
			filemtime( $css )
		);
	}
);

/**
 * Whether a block render is happening for an editor preview.
 *
 * Block templates use this to skip inline <script> output, which would
 * otherwise execute inside the editor canvas now that blocks preview there.
 *
 * @return bool
 */
function cb_is_block_preview() {
	return is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || wp_is_json_request();
}

/**
 * Registers custom ACF blocks.
 *
 * This function checks if the ACF plugin is active and registers custom blocks
 * for use in the WordPress block editor. Each block has its own name, title,
 * category, icon, render template, and supports various features.
 */
function acf_blocks() {
	if ( function_exists( 'acf_register_block_type' ) ) {

		// INSERT NEW BLOCKS HERE.

		acf_register_block_type(
			array(
				'name'            => 'cb_projects_index',
				'title'           => __( 'CB Projects Index' ),
				'category'        => 'layout',
				'icon'            => 'grid-view',
				'render_template' => 'blocks/cb-projects-index.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_heading',
				'title'           => __( 'CB Heading' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-heading.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_case_study_sidebar',
				'title'           => __( 'CB Case Study Sidebar' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-case-study-sidebar.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_product_used',
				'title'           => __( 'CB Product Used' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-product-used.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_related_projects',
				'title'           => __( 'CB Related Projects' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-related-projects.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_spec_bar',
				'title'           => __( 'CB Spec Bar' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-spec-bar.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_products_nav',
				'title'           => __( 'CB Products Nav' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-products-nav.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_faqs',
				'title'           => __( 'CB FAQs' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-faqs.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_client_projects_gallery',
				'title'           => __( 'CB Client Projects Gallery' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-client-projects-gallery.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_product_hero',
				'title'           => __( 'CB Product Hero' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-product-hero.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_contact',
				'title'           => __( 'CB Contact' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-contact.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
					'color'     => array(
						'background' => true,
					),
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_text_stats',
				'title'           => __( 'CB Text Stats' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-text-stats.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_steps',
				'title'           => __( 'CB Steps' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-steps.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_specs',
				'title'           => __( 'CB Specs' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-specs.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,
					'color'     => array(
						'background' => true,
					),
				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_projects_grid',
				'title'           => __( 'CB Projects Grid' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-projects-grid.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_image_cta',
				'title'           => __( 'CB Image CTA' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-image-cta.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_why_split',
				'title'           => __( 'CB Why Split' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-why-split.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_products_grid',
				'title'           => __( 'CB Products Grid' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-products-grid.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_configurator',
				'title'           => __( 'CB Configurator' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-configurator.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_applications_grid',
				'title'           => __( 'CB Applications Grid' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-applications-grid.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_selected_clients',
				'title'           => __( 'CB Selected Clients' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-selected-clients.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_intro',
				'title'           => __( 'CB Intro' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-intro.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_marquee_stats',
				'title'           => __( 'CB Marquee Stats' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-marquee-stats.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

		acf_register_block_type(
			array(
				'name'            => 'cb_home_hero',
				'title'           => __( 'CB Home Hero' ),
				'category'        => 'layout',
				'icon'            => 'cover-image',
				'render_template' => 'blocks/cb-home-hero.php',
				'mode'            => 'preview',
				'supports'        => array(
					'mode'      => false,
					'anchor'    => true,
					'className' => true,
					'align'     => true,

				),
			)
		);

	}
}
add_action( 'acf/init', 'acf_blocks' );

// Auto-sync ACF field groups from acf-json folder.
add_filter(
	'acf/settings/save_json',
	function ( $path ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
		return get_stylesheet_directory() . '/acf-json';
	}
);

add_filter(
	'acf/settings/load_json',
	function ( $paths ) {
		unset( $paths[0] );
		$paths[] = get_stylesheet_directory() . '/acf-json';
		return $paths;
	}
);

/**
 * Modifies the arguments for specific core block types.
 *
 * @param array  $args The block type arguments.
 * @param string $name The block type name.
 * @return array Modified block type arguments.
 */
function core_block_type_args( $args, $name ) {

	if ( 'core/paragraph' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}
	if ( 'core/heading' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}
	if ( 'core/list' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}
	if ( 'core/separator' === $name ) {
		$args['render_callback'] = 'modify_core_add_container';
	}

	return $args;
}
add_filter( 'register_block_type_args', 'core_block_type_args', 10, 3 );

/**
 * Helper function to detect if footer.php is being rendered.
 *
 * @return bool True if footer.php is being rendered, false otherwise.
 */
function is_footer_rendering() {
	$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
	foreach ( $backtrace as $trace ) {
		if ( isset( $trace['file'] ) && basename( $trace['file'] ) === 'footer.php' ) {
			return true;
		}
	}
	return false;
}

/**
 * Adds a container div around the block content unless footer.php is being rendered.
 *
 * @param array  $attributes The block attributes.
 * @param string $content    The block content.
 * @return string The modified block content wrapped in a container div.
 */
function modify_core_add_container( $attributes, $content ) {
	if ( is_footer_rendering() ) {
		return $content;
	}

	ob_start();
	?>
	<div class="container">
		<?= wp_kses_post( $content ); ?>
	</div>
	<?php
	$content = ob_get_clean();
	return $content;
}

/**
 * Register block styles for the case study narrative.
 *
 * The narrative is built from core blocks; these cover the two treatments core
 * has no equivalent for. Styles live in src/sass/theme/_case_study_narrative.scss.
 *
 * @return void
 */
function cb_register_block_styles() {
	register_block_style(
		'core/list',
		array(
			'name'  => 'cb-ticked',
			'label' => __( 'Ticked list' ),
		)
	);

	register_block_style(
		'core/paragraph',
		array(
			'name'  => 'cb-callout',
			'label' => __( 'Callout' ),
		)
	);
}
add_action( 'init', 'cb_register_block_styles' );
