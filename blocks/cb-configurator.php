<?php
/**
 * Block template for CB Configurator.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$html_tag = get_field( 'tag' );
$headline = get_field( 'headline' );
$intro    = get_field( 'intro' );
$features = get_field( 'features' );
$cta      = get_field( 'cta' );
$image    = get_field( 'image' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);
?>
<section class="config" id="configurator">
	<div class="container">
		<div class="config-inner row align-items-center g-4 g-lg-5">
			<div class="col-lg-6">
				<?php
				if ( $html_tag ) {
					?>
				<span class="config-tag"><?= esc_html( $html_tag ); ?></span>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="config-h2 h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $intro ) {
					?>
				<div class="config-body prose-md"><?= wp_kses_post( $intro ); ?></div>
					<?php
				}
				if ( $features ) {
					?>
				<ul class="config-features">
					<?= wp_kses_post( cb_list( $features ) ); ?>
				</ul>
					<?php
				}
				if ( ! empty( $cta['url'] ) ) {
					?>
					<a href="<?= esc_url( $cta['url'] ); ?>"
						<?= ! empty( $cta['target'] ) ? ' target="' . esc_attr( $cta['target'] ) . '" rel="noopener"' : ''; ?>
						class="btn btn-primary">
						<?= esc_html( ! empty( $cta['title'] ) ? $cta['title'] : 'Launch the configurator' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
							<path d="M5 12h14M12 5l7 7-7 7"/>
						</svg>
					</a>
					<?php
				}
				?>
			</div>
			<?php
			if ( ! empty( $image['ID'] ) ) {
				?>
			<div class="col-lg-6">
				<div class="config-img">
					<?php
					echo wp_get_attachment_image(
						$image['ID'],
						'large',
						false,
						array( 'alt' => esc_attr( $image['alt'] ) )
					);
					?>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
