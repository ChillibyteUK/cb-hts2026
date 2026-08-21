<?php
/**
 * Block template for CB Client Projects Gallery.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$images = get_field( 'images' );

if ( empty( $images ) ) {
	return;
}

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'heading' );
$intro    = get_field( 'intro' );
$layout   = get_field( 'layout' );

// "feature" leads with one large tile and follows with half-width tiles — the
// project gallery on a case study. Default is the repeating five-tile mosaic.
$is_feature = 'feature' === $layout;

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

// Named apart from the per-tile $classes reused inside the loops below.
$section_classes = array( 'client-gallery' );

if ( ! empty( $block['className'] ) ) {
	$section_classes[] = $block['className'];
}

// Falls back to 'gallery' so "View the gallery" links work without the editor
// having to set an anchor on every case study.
$anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'gallery';

?>
<section class="<?= esc_attr( implode( ' ', $section_classes ) ); ?>" id="<?= esc_attr( $anchor ); ?>">
	<div class="container">
		<?php
		if ( $eyebrow ) {
			?>
			<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
			<?php
		}
		?>
		<div class="row pb-5">
			<div class="col-md-6">
				<?php
				if ( $headline ) {
					?>
				<h2 class="intro-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
			<div class="col-md-5 offset-md-1">
			<?php
			if ( $intro ) {
				?>
				<div class="client-gallery-intro"><?= wp_kses_post( $intro ); ?></div>
				<?php
			}
			?>
			</div>
		</div>

		<div class="client-gallery-grid<?= $is_feature ? ' client-gallery-grid--feature' : ''; ?>">
			<?php
			if ( $is_feature ) {
				foreach ( $images as $i => $image_id ) {
					$caption = wp_get_attachment_caption( $image_id );
					$classes = array( 'client-gallery-item', 'client-gallery-item--feature-tile' );

					if ( 0 === $i ) {
						$classes[] = 'client-gallery-item--lead';
					}
					?>
			<a href="<?= esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>"
				class="<?= esc_attr( implode( ' ', $classes ) ); ?>"
				data-glightbox="<?= $caption ? esc_attr( 'title: ' . $caption ) : 'type: image'; ?>">
					<?= wp_get_attachment_image( $image_id, 'medium_large', false, array( 'loading' => 0 === $i ? 'eager' : 'lazy' ) ); ?>
					<?php
					if ( $caption ) {
						?>
				<span class="client-gallery-item-caption"><?= esc_html( $caption ); ?></span>
						<?php
					}
					?>
			</a>
					<?php
				}
			} else {
				foreach ( $images as $i => $image_id ) {
					$group    = intdiv( $i, 5 );
					$pos      = $i % 5;
					$row_base = $group * 3 + 1;
					$classes  = array( 'client-gallery-item' );
					$style    = '';

					if ( 0 === $group % 2 ) {
						// Pattern A: large block left, two stacked right, then wide + single.
						switch ( $pos ) {
							case 0:
								$style = "grid-column: 1 / 3; grid-row: {$row_base} / " . ( $row_base + 2 ) . ';';
								break;
							case 1:
								$style     = "grid-column: 3 / 4; grid-row: {$row_base} / " . ( $row_base + 1 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 2:
								$style     = 'grid-column: 3 / 4; grid-row: ' . ( $row_base + 1 ) . ' / ' . ( $row_base + 2 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 3:
								$style = 'grid-column: 1 / 3; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								break;
							case 4:
								$style     = 'grid-column: 3 / 4; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
						}
					} else {
						// Pattern B (flipped): two stacked left, large block right, then wide + single.
						switch ( $pos ) {
							case 0:
								$style     = "grid-column: 1 / 2; grid-row: {$row_base} / " . ( $row_base + 1 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 1:
								$style = "grid-column: 2 / 4; grid-row: {$row_base} / " . ( $row_base + 2 ) . ';';
								break;
							case 2:
								$style     = 'grid-column: 1 / 2; grid-row: ' . ( $row_base + 1 ) . ' / ' . ( $row_base + 2 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
							case 3:
								$style = 'grid-column: 1 / 3; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								break;
							case 4:
								$style     = 'grid-column: 3 / 4; grid-row: ' . ( $row_base + 2 ) . ' / ' . ( $row_base + 3 ) . ';';
								$classes[] = 'client-gallery-item--small';
								break;
						}
					}
					?>
			<a href="<?= esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>"
				class="<?= esc_attr( implode( ' ', $classes ) ); ?>"
				style="<?= esc_attr( $style ); ?>"
				data-glightbox="type: image">
					<?= wp_get_attachment_image( $image_id, 'medium_large', false, array( 'loading' => 'lazy' ) ); ?>
			</a>
					<?php
				}
			}
			?>
		</div>
	</div>
</section>

<?php
add_action(
	'wp_footer',
	function () {
		?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	if (typeof GLightbox !== 'undefined') {
		GLightbox({ selector: '.client-gallery-item' });
	}
});
</script>
		<?php
	},
	999
);
