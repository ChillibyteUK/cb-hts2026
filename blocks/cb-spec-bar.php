<?php
/**
 * Block template for CB Spec Bar.
 *
 * Navy key/value strip, sits directly under a hero.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$items = get_field( 'items' );

if ( empty( $items ) ) {
	return;
}

$classes = array( 'spec-bar' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>"<?= wp_kses_post( $anchor ); ?>>
	<div class="container">
		<div class="spec-bar-grid">
			<?php
			foreach ( $items as $item ) {
				if ( empty( $item['label'] ) && empty( $item['value'] ) ) {
					continue;
				}
				?>
			<div class="spec-bar-item">
				<?php
				if ( ! empty( $item['label'] ) ) {
					?>
				<div class="spec-bar-label"><?= esc_html( $item['label'] ); ?></div>
					<?php
				}
				if ( ! empty( $item['value'] ) ) {
					?>
				<div class="spec-bar-value">
					<?= wp_kses( $item['value'], array( 'br' => array() ) ); ?>
					<?php
					if ( ! empty( $item['unit'] ) ) {
						?>
					<span class="spec-bar-unit"><?= esc_html( $item['unit'] ); ?></span>
						<?php
					}
					?>
				</div>
					<?php
				}
				?>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
