<?php
/**
 * Block template for CB Product Used.
 *
 * A single linked card promoting the product featured in a case study. Copy is
 * pulled from the linked product post, with optional per-placement overrides.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$product = get_field( 'product' );

if ( is_array( $product ) ) {
	$product = reset( $product );
}

if ( ! $product instanceof WP_Post ) {
	return;
}

$eyebrow = get_field( 'eyebrow' );
$heading = get_field( 'heading' );
$summary = get_field( 'summary' );
$image   = get_field( 'image' );

if ( ! $eyebrow ) {
	$eyebrow = 'Product used in this case study';
}

if ( ! $heading ) {
	$heading = get_the_title( $product->ID );
}

if ( ! $summary ) {
	$summary = get_the_excerpt( $product->ID );
}

$image_id = ! empty( $image['ID'] ) ? (int) $image['ID'] : get_post_thumbnail_id( $product->ID );

$classes = array( 'product-used' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>"<?= wp_kses_post( $anchor ); ?>>
	<div class="container">
		<a class="product-used-card" href="<?= esc_url( get_permalink( $product->ID ) ); ?>">
			<div class="product-used-media">
				<?php
				if ( $image_id ) {
					echo wp_get_attachment_image(
						$image_id,
						'medium_large',
						false,
						array(
							'class'   => 'product-used-img',
							'loading' => 'lazy',
							'alt'     => esc_attr( $heading ),
						)
					);
				}
				?>
			</div>
			<div class="product-used-body">
				<div class="product-used-eyebrow tag"><?= esc_html( $eyebrow ); ?></div>
				<h2 class="product-used-title h3"><?= esc_html( $heading ); ?></h2>
				<?php
				if ( $summary ) {
					?>
				<p class="product-used-summary"><?= esc_html( $summary ); ?></p>
					<?php
				}
				?>
				<span class="product-used-link">
					<?= esc_html( 'Explore ' . get_the_title( $product->ID ) ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7"/>
					</svg>
				</span>
			</div>
		</a>
	</div>
</section>
