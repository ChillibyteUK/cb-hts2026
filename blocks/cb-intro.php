<?php
/**
 * Block template for CB Intro.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = get_field( 'eyebrow' );
$headline   = get_field( 'headline' );
$body       = get_field( 'body' );
$signature  = get_field( 'signature' );
$highlights = get_field( 'highlights' );

$headline_allowed  = array(
	'span' => array(),
	'br'   => array(),
);
$signature_allowed = array(
	'strong' => array(),
	'em'     => array(),
	'br'     => array(),
);
?>
<section class="intro">
	<div class="container">
		<div class="intro-inner">
			<div class="intro-col-head">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="intro-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
			<div class="intro-col-body">
				<?php
				if ( $body ) {
					?>
				<div class="intro-body"><?= wp_kses_post( $body ); ?></div>
					<?php
				}
				if ( $signature ) {
					?>
				<div class="intro-signature">
					<div><?= wp_kses( $signature, $signature_allowed ); ?></div>
				</div>
					<?php
				}
				if ( $highlights ) {
					$pillar_lines = array_filter( explode( "\n", $highlights ) );
					if ( $pillar_lines ) {
						?>
				<div class="intro-pillars">
						<?php
						foreach ( $pillar_lines as $pillar ) {
							?>
					<span class="intro-pill">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><polyline points="20 6 9 17 4 12" /></svg>
							<?= esc_html( trim( $pillar ) ); ?>
					</span>
							<?php
						}
						?>
				</div>
						<?php
					}
				}
				?>
			</div>
		</div>
	</div>
</section>