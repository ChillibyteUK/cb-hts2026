<?php
/**
 * Block template for CB Why Split.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$body     = get_field( 'body' );
$stats    = get_field( 'stats' );
$reasons  = get_field( 'reasons' );

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
<section class="why-split" id="why">
	<div class="why-split-watermark" aria-hidden="true"></div>
	<div class="container">
		<div class="row g-5 g-xl-6">
			<div class="col-lg-5">
				<div class="why-split-left">
					<?php
					if ( $eyebrow ) {
						?>
					<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
						<?php
					}
					if ( $headline ) {
						?>
					<h2 class="why-split-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
						<?php
					}
					if ( $body ) {
						?>
					<div class="why-split-copy prose-md"><?= wp_kses( $body, $br_allowed ); ?></div>
						<?php
					}

					if ( $stats ) {
						?>
					<div class="why-split-stats row row-cols-2 g-4">
						<?php
						foreach ( $stats as $stat ) {
							?>
						<div class="col">
							<?php
							if ( ! empty( $stat['value'] ) ) {
								?>
							<div class="why-split-stat-value stat-lg"><?= wp_kses( $stat['value'], $stat_value_allowed ); ?></div>
								<?php
							}
							if ( ! empty( $stat['label'] ) ) {
								?>
							<div class="why-split-stat-label"><?= wp_kses( $stat['label'], $br_allowed ); ?></div>
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

			<div class="col-lg-7">
				<?php
				if ( $reasons ) {
					?>
				<div class="why-split-reasons">
					<?php
					foreach ( $reasons as $index => $reason ) {
						?>
					<div class="why-split-reason">
						<div class="why-split-reason-num"><?= esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></div>
						<div>
							<?php
							if ( ! empty( $reason['title'] ) ) {
								?>
							<h3 class="why-split-reason-title h6"><?= esc_html( $reason['title'] ); ?></h3>
								<?php
							}
							if ( ! empty( $reason['body'] ) ) {
								?>
							<div class="why-split-reason-body"><?= wp_kses( $reason['body'], $br_allowed ); ?></div>
								<?php
							}
							?>
						</div>
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

<?php if ( ! cb_is_block_preview() ) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
	if (
		!('IntersectionObserver' in window) ||
		window.matchMedia('(prefers-reduced-motion: reduce)').matches
	) {
		return;
	}

	var section = document.getElementById('why');

	if (!section) {
		return;
	}

	var observer = new IntersectionObserver(
		function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) {
					return;
				}

				section.classList.add('is-in-view');
				observer.disconnect();
			});
		},
		{
			threshold: 0.2,
			rootMargin: '0px 0px -10% 0px',
		}
	);

	observer.observe(section);
});
</script>
<?php endif; ?>
