<?php
/**
 * Block template for CB Steps.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$intro    = get_field( 'intro' );
$steps    = get_field( 'steps' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$br_allowed = array(
	'br' => array(),
);

$step_defaults = array(
	array(
		'num'   => 'STEP 01',
		'title' => 'Free Consultation',
		'body'  => 'Tell us your requirements, timeline and site constraints. We advise on the right product and size.',
		'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>',
	),
	array(
		'num'   => 'STEP 02',
		'title' => 'Site Survey',
		'body'  => 'We visit your site to assess ground conditions, access routes, planning and utility connections.',
		'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>',
	),
	array(
		'num'   => 'STEP 03',
		'title' => 'Design &amp; Quote',
		'body'  => 'Detailed drawings and a fixed-price quote within 5 working days. Rental, finance or purchase.',
		'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
	),
	array(
		'num'   => 'STEP 04',
		'title' => 'Manufacture &amp; Deliver',
		'body'  => 'Built at our German facility and delivered on the agreed date — typically within 14 days.',
		'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
	),
	array(
		'num'   => 'STEP 05',
		'title' => 'Install &amp; Handover',
		'body'  => 'Our crews erect your structure on site and hand over with full Building Regs compliance documentation.',
		'icon'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
	),
);
?>
<section class="process">
	<div class="container">
		<div class="process-header">
			<?php
			if ( $eyebrow ) {
				?>
			<div class="eyebrow center"><?= esc_html( $eyebrow ); ?></div>
				<?php
			}
			if ( $headline ) {
				?>
			<h2 class="process-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php
			}
			if ( $intro ) {
				?>
			<p class="process-sub prose-md"><?= wp_kses( $intro, $br_allowed ); ?></p>
				<?php
			}
			?>
		</div>

		<div class="process-steps">
			<?php
			foreach ( $step_defaults as $index => $step ) {
				$body = ! empty( $steps[ $index ]['body'] ) ? $steps[ $index ]['body'] : $step['body'];
				?>
			<div class="process-step">
				<div class="step-num"><?= esc_html( $step['num'] ); ?></div>
				<div class="step-icon"><?= $step['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<div class="step-title"><?= wp_kses_post( $step['title'] ); ?></div>
				<div class="step-body"><?= wp_kses( $body, $br_allowed ); ?></div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
