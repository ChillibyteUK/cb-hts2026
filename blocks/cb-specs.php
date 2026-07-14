<?php
/**
 * Block template for CB Specs.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$intro    = get_field( 'intro' );
$rows     = get_field( 'rows' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$br_allowed = array(
	'br' => array(),
);

$bg = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : 'has-paper-background-color';

?>
<section class="specs" id="specs">
	<div class="specs-watermark" aria-hidden="true"></div>
	<div class="container">
		<div class="row g-5 g-xl-6 align-items-start">
			<div class="col-lg-5">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="specs-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $intro ) {
					?>
				<div class="specs-intro prose-md"><?= wp_kses( $intro, $br_allowed ); ?></div>
					<?php
				}
				?>
			</div>

			<div class="col-lg-7">
				<?php
				if ( $rows ) {
					?>
				<table class="specs-table">
					<tbody>
						<?php
						foreach ( $rows as $row ) {
							if ( empty( $row['label'] ) && empty( $row['value'] ) ) {
								continue;
							}
							?>
							<tr>
								<td class="specs-label"><?= esc_html( $row['label'] ); ?></td>
								<td class="specs-value"><?= wp_kses( $row['value'], $br_allowed ); ?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
	if (
		!('IntersectionObserver' in window) ||
		window.matchMedia('(prefers-reduced-motion: reduce)').matches
	) {
		return;
	}

	var section = document.getElementById('specs');

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
