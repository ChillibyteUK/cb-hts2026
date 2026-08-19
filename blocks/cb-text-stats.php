<?php
/**
 * Block template for CB Text Stats.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow    = get_field( 'eyebrow' );
$headline   = get_field( 'headline' );
$body       = get_field( 'body' );
$block_link = get_field( 'link' );
$stats      = get_field( 'stats' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$stat_value_allowed = array(
	'sup' => array(),
	'br'  => array(),
);

$br_allowed = array(
	'br' => array(),
);
?>
<section class="parent">
	<div class="container">
		<div class="parent-inner row align-items-center g-5 g-xl-6">
			<div class="col-lg-7">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow eyebrow--light"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="parent-h2 h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $body ) {
					?>
				<div class="parent-body prose-md"><?= wp_kses( $body, $br_allowed ); ?></div>
					<?php
				}
				if ( ! empty( $block_link['url'] ) ) {
					?>
				<a href="<?= esc_url( $block_link['url'] ); ?>"
					<?= ! empty( $block_link['target'] ) ? ' target="' . esc_attr( $block_link['target'] ) . '" rel="noopener"' : ''; ?>
					class="btn btn-outline-light">
					<?= esc_html( ! empty( $block_link['title'] ) ? $block_link['title'] : 'Learn More' ); ?>
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
						<path d="M5 12h14M12 5l7 7-7 7"/>
					</svg>
				</a>
					<?php
				}
				?>
			</div>

			<div class="col-lg-5">
				<?php
				if ( $stats ) {
					?>
				<div class="parent-stats">
					<?php
					foreach ( $stats as $stat ) {
						?>
					<div class="parent-stat">
						<?php
						if ( ! empty( $stat['value'] ) ) {
							?>
						<div class="parent-stat-num stat-lg"><?= wp_kses( $stat['value'], $stat_value_allowed ); ?></div>
							<?php
						}
						if ( ! empty( $stat['label'] ) ) {
							?>
						<div class="parent-stat-label"><?= wp_kses( $stat['label'], $br_allowed ); ?></div>
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
	</div>
</section>
