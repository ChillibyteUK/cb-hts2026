<?php
/**
 * Block template for CB Image CTA.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$block_id = $block['anchor'] ?? $block['id'] ?? wp_unique_id( 'cb-image-cta-' );
$image    = get_field( 'background_image' );
$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$content  = get_field( 'content' );
$button   = get_field( 'button' );
$button_2 = get_field( 'button_secondary' );

$section_classes = array( 'image-cta' );

if ( ! empty( $block['className'] ) ) {
	$section_classes[] = $block['className'];
}

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);
?>
<section id="<?= esc_attr( $block_id ); ?>" class="<?= esc_attr( implode( ' ', $section_classes ) ); ?>">
	<?php
	if ( ! empty( $image['ID'] ) ) {
		?>
	<div class="image-cta-media" aria-hidden="true">
		<?php
		echo wp_get_attachment_image(
			$image['ID'],
			'full',
			false,
			array(
				'class' => 'image-cta-media-img',
				'alt'   => '',
			)
		);
		?>
	</div>
		<?php
	}
	?>

	<div class="image-cta-overlay" aria-hidden="true"></div>

	<div class="container">
		<div class="image-cta-inner">
			<?php
			if ( $eyebrow ) {
				?>
			<div class="eyebrow eyebrow--plain light center"><?= esc_html( $eyebrow ); ?></div>
				<?php
			}

			if ( $headline ) {
				?>
			<h2 class="image-cta-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php
			}

			if ( $content ) {
				?>
			<div class="image-cta-content prose-md"><?= wp_kses_post( $content ); ?></div>
				<?php
			}

			if ( ! empty( $button['url'] ) || ! empty( $button_2['url'] ) ) {
				?>
			<div class="image-cta-actions">
				<?php
				if ( ! empty( $button['url'] ) ) {
					?>
				<a href="<?= esc_url( $button['url'] ); ?>"
					<?= ! empty( $button['target'] ) ? ' target="' . esc_attr( $button['target'] ) . '" rel="noopener"' : ''; ?>
					class="btn btn-primary w-100 w-md-auto">
					<?= esc_html( ! empty( $button['title'] ) ? $button['title'] : 'Get Started' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7"/>
					</svg>
				</a>
					<?php
				}

				if ( ! empty( $button_2['url'] ) ) {
					?>
				<a href="<?= esc_url( $button_2['url'] ); ?>"
					<?= ! empty( $button_2['target'] ) ? ' target="' . esc_attr( $button_2['target'] ) . '" rel="noopener"' : ''; ?>
					class="btn btn-outline-light w-100 w-md-auto">
					<?= esc_html( ! empty( $button_2['title'] ) ? $button_2['title'] : 'Learn More' ); ?>
				</a>
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

<?php if ( ! empty( $image['ID'] ) ) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	var section = document.getElementById(<?= wp_json_encode( $block_id ); ?>);
	if (!section) return;

	var ticking = false;

	function update() {
		var rect = section.getBoundingClientRect();
		var windowHeight = window.innerHeight;

		if (rect.bottom > 0 && rect.top < windowHeight) {
			var percent = (windowHeight - rect.top) / (windowHeight + rect.height);
			percent = Math.max(0, Math.min(1, percent));
			var translateY = (percent - 0.5) * 240;
			section.style.setProperty('--image-cta-parallax-y', translateY.toFixed(1) + 'px');
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
