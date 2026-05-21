<?php
/**
 * Block template for CB Selected Clients.
 *
 * Renders a marquee of client logos defined in
 * Site-Wide Settings > Clients > Client Logos.
 * Logos are expected to be 16:9, pre-cropped and colour-corrected.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$logos = get_field( 'client_logos', 'option' );
if ( empty( $logos ) || ! is_array( $logos ) ) {
	return;
}
?>
<section class="clients-strip">
	<div class="container">
		<div class="clients-inner">
			<div class="clients-label">Selected clients</div>
			<div class="clients-marquee" data-marquee data-marquee-speed="50" aria-label="Selected clients">
				<div class="clients-track" data-marquee-track>
					<?php
					foreach ( $logos as $row ) {
						$name = isset( $row['name'] ) ? $row['name'] : '';
						$logo = isset( $row['logo'] ) ? $row['logo'] : null;

						if ( empty( $logo ) || empty( $logo['ID'] ) ) {
							continue;
						}

						echo wp_get_attachment_image(
							$logo['ID'],
							'medium',
							false,
							array(
								'class' => 'client-logo',
								'alt'   => $name,
							)
						);
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
cb_marquee_script();
