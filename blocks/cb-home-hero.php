<?php
/**
 * Block template for CB Home Hero.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$block_id           = $block['anchor'] ?? $block['id'] ?? wp_unique_id( 'cb-home-hero-' );
$meta_items         = get_field( 'meta_items' );
$h1_line1_text      = get_field( 'h1_line1_text' );
$h1_line1_highlight = get_field( 'h1_line1_highlight' );
$h1_line2_text      = get_field( 'h1_line2_text' );
$h1_line2_highlight = get_field( 'h1_line2_highlight' );
$lede               = get_field( 'lede' );
$bullets            = get_field( 'bullets' );
$cta_primary        = get_field( 'cta_primary' );
$cta_secondary      = get_field( 'cta_secondary' );
$image              = get_field( 'image' );
$badge_number       = get_field( 'badge_number' );
$badge_suffix       = get_field( 'badge_suffix' );
$badge_label        = get_field( 'badge_label' );

$classes = 'hero';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}
?>
<section id="<?= esc_attr( $block_id ); ?>" class="<?= esc_attr( $classes ); ?>">
	<div class="container">
		<div class="hero-split">
			<div class="hero-content">
				<?php
				if ( $meta_items ) {
					?>
					<ul class="hero-meta">
						<?= wp_kses_post( cb_list( $meta_items ) ); ?>
					</ul>
					<?php
				}

				if ( $h1_line1_text || $h1_line1_highlight || $h1_line2_text || $h1_line2_highlight ) {
					?>
					<h1 class="hero-h1">
						<?php
						if ( $h1_line1_text ) {
							echo esc_html( $h1_line1_text );
						}
						if ( $h1_line1_highlight ) {
							?>
						<span class="underline"><?= esc_html( $h1_line1_highlight ); ?></span>
							<?php
						}
						if ( ( $h1_line1_text || $h1_line1_highlight ) && ( $h1_line2_text || $h1_line2_highlight ) ) {
							?>
						<br>
							<?php
						}
						if ( $h1_line2_text ) {
							echo esc_html( $h1_line2_text );
						}
						if ( $h1_line2_highlight ) {
							?>
						<span class="accent"><?= esc_html( $h1_line2_highlight ); ?></span>
							<?php
						}
						?>
					</h1>
					<?php
				}

				if ( $lede ) {
					?>
					<div class="hero-lede"><?= wp_kses_post( $lede ); ?></div>
					<?php
				}

				if ( $bullets ) {
					?>
					<ul class="hero-bullets">
						<?= wp_kses_post( cb_list( $bullets ) ); ?>
					</ul>
					<?php
				}

				if ( $cta_primary || $cta_secondary ) {
					?>
					<div class="hero-actions">
						<?php
						if ( $cta_primary ) {
							?>
							<a href="<?= esc_url( $cta_primary['url'] ); ?>"
								class="btn btn-primary"
								<?php
								if ( ! empty( $cta_primary['target'] ) ) {
									?>
								target="<?= esc_attr( $cta_primary['target'] ); ?>" rel="noopener"
									<?php
								}
								?>
								>
								<?= esc_html( $cta_primary['title'] ); ?>
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
							</a>
							<?php
						}
						if ( $cta_secondary ) {
							?>
							<a href="<?= esc_url( $cta_secondary['url'] ); ?>"
								class="btn btn-outline-dark"
								<?php
								if ( ! empty( $cta_secondary['target'] ) ) {
									?>
								target="<?= esc_attr( $cta_secondary['target'] ); ?>" rel="noopener"
									<?php
								}
								?>
								>
								<?= esc_html( $cta_secondary['title'] ); ?>
							</a>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>

			</div>

			<div class="hero-visual">
				<?php
				if ( $image ) {
					?>
					<div class="hero-img-wrap">
						<?= wp_get_attachment_image( $image['ID'], 'full', false, array( 'class' => 'hero-img' ) ); ?>
					</div>
					<?php
				}

				if ( $badge_number || $badge_label ) {
					?>
					<div class="hero-badge">
						<div>
							<?php
							if ( $badge_number ) {
								?>
								<div class="hero-badge-num">
									<?= esc_html( $badge_number ); ?>
									<?php
									if ( $badge_suffix ) {
										?>
									<sup><?= esc_html( $badge_suffix ); ?></sup>
										<?php
									}
									?>
								</div>
								<?php
							}
							if ( $badge_label ) {
								?>
								<div class="hero-badge-label"><?= wp_kses( $badge_label, array( 'br' => array() ) ); ?></div>
								<?php
							}
							?>
						</div>
					</div>
					<?php
				}
				?>
			</div>

	</div>
</div>
</section>

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
