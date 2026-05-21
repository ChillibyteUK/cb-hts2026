<?php
/**
 * Block template for CB Image CTA.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$image    = get_field( 'background_image' );
$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$content  = get_field( 'content' );
$button   = get_field( 'button' );
$button_2 = get_field( 'button_secondary' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);
?>
<section class="image-cta">
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
