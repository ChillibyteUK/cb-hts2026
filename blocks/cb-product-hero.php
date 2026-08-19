<?php
/**
 * Block template for CB Product Hero.
 *
 * Markup lives in template-parts/hero-split.php, shared with single-project.php.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$block_id = $block['anchor'] ?? $block['id'] ?? wp_unique_id( 'cb-product-hero-' );
$image    = get_field( 'image' );

$classes = array();

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes[] = 'align' . $block['align'];
}

get_template_part(
	'template-parts/hero-split',
	null,
	array(
		'id'            => $block_id,
		'classes'       => implode( ' ', $classes ),
		'tag'           => get_field( 'meta_items' ),
		'h1'            => get_field( 'h1' ),
		'subtitle'      => get_field( 'sub' ),
		'lede'          => get_field( 'lede' ),
		'bullets'       => get_field( 'bullets' ),
		'image_id'      => ! empty( $image['ID'] ) ? (int) $image['ID'] : 0,
		'badge_number'  => get_field( 'badge_number' ),
		'badge_suffix'  => get_field( 'badge_suffix' ),
		'badge_label'   => get_field( 'badge_label' ),
		'cta_primary'   => get_field( 'cta_primary' ),
		'cta_secondary' => get_field( 'cta_secondary' ),
	)
);
?>

<?php if ( $image ) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	var section = document.getElementById(<?= wp_json_encode( $block_id ); ?>);
	if (!section) return;

	var ticking = false;

	function update() {
		var rect = section.getBoundingClientRect();
		var windowHeight = window.innerHeight;

		if (rect.bottom > 0 && rect.top < windowHeight) {
			var percent = (windowHeight - rect.top) / (windowHeight + rect.height);
			percent = Math.max(0, Math.min(1, percent));
			var translateY = (percent - 0.5) * 120;
			section.style.setProperty('--hero-parallax-y', translateY.toFixed(1) + 'px');
		}

		ticking = false;
	}

	function onScroll() {
		if (!ticking) {
			window.requestAnimationFrame(update);
			ticking = true;
		}
	}

	window.addEventListener('scroll', onScroll, { passive: true });
	window.addEventListener('resize', onScroll);
	onScroll();
});
</script>
<?php endif; ?>
