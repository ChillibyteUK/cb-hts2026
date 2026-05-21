<?php
/**
 * Block template for CB Contact.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow       = get_field( 'eyebrow' );
$headline      = get_field( 'headline' );
$body          = get_field( 'body' );
$coverage      = get_field( 'coverage' );
$note_title    = get_field( 'note_title' );
$note_body     = get_field( 'note_body' );
$form_title    = get_field( 'form_title' );
$form_subtitle = get_field( 'form_subtitle' );
$form_code     = get_field( 'form_shortcode' );

$phone = get_field( 'contact_phone', 'option' );
$email = get_field( 'contact_email', 'option' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$br_allowed = array(
	'br' => array(),
);
?>
<section class="contact" id="contact">
	<div class="container">
		<div class="contact-inner row g-5 g-xl-6 align-items-start">
			<div class="col-lg-5">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="contact-h2 h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				if ( $body ) {
					?>
				<div class="contact-body prose-md"><?= wp_kses( $body, $br_allowed ); ?></div>
					<?php
				}
				?>

				<div class="contact-details">
					<?php if ( $phone ) { ?>
						<div class="contact-detail">
							<div class="contact-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.5 19.79 19.79 0 0 1 1.59 4.9 2 2 0 0 1 3.59 2.73h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 10.36a16 16 0 0 0 6 6l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7a2 2 0 0 1 1.72 2.02z"/></svg>
							</div>
							<div>
								<div class="contact-detail-label">Phone</div>
								<div class="contact-detail-value"><a href="tel:<?= esc_attr( parse_phone( $phone ) ); ?>"><?= esc_html( $phone ); ?></a></div>
							</div>
						</div>
					<?php } ?>

					<?php if ( $email ) { ?>
						<div class="contact-detail">
							<div class="contact-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
							</div>
							<div>
								<div class="contact-detail-label">Email</div>
								<div class="contact-detail-value"><a href="mailto:<?= esc_attr( antispambot( $email ) ); ?>"><?= esc_html( antispambot( $email ) ); ?></a></div>
							</div>
						</div>
					<?php } ?>

					<?php if ( $coverage ) { ?>
						<div class="contact-detail">
							<div class="contact-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
							</div>
							<div>
								<div class="contact-detail-label">Coverage</div>
								<div class="contact-detail-value"><?= wp_kses( $coverage, $br_allowed ); ?></div>
							</div>
						</div>
					<?php } ?>

					<?php if ( $note_title || $note_body ) { ?>
						<div class="contact-note">
							<?php if ( $note_title ) { ?>
								<div class="contact-detail-label contact-note-title"><?= esc_html( $note_title ); ?></div>
							<?php } ?>
							<?php if ( $note_body ) { ?>
								<div class="contact-note-body"><?= wp_kses( $note_body, $br_allowed ); ?></div>
							<?php } ?>
						</div>
					<?php } ?>
				</div>
			</div>

			<div class="col-lg-7">
				<div class="contact-form">
					<?php if ( $form_title ) { ?>
						<div class="form-title"><?= esc_html( $form_title ); ?></div>
					<?php } ?>
					<?php if ( $form_subtitle ) { ?>
						<div class="form-subtitle"><?= wp_kses( $form_subtitle, $br_allowed ); ?></div>
					<?php } ?>
					<?php if ( $form_code ) { ?>
						<div class="contact-form-shortcode"><?= do_shortcode( $form_code ); ?></div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>
