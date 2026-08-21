<?php
/**
 * Block template for CB Downloads.
 *
 * Grid of downloadable documents — data sheets, certifications, brochures.
 * File type and size are read from the attachment rather than typed by hand.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$intro    = get_field( 'intro' );
$items    = get_field( 'items' );

if ( empty( $items ) ) {
	return;
}

// Support Gutenberg color picker.
$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$section_id = ! empty( $block['anchor'] ) ? $block['anchor'] : 'downloads';
$extra      = $block['className'] ?? '';

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$br_allowed = array(
	'br' => array(),
);
?>
<section class="downloads <?= esc_attr( trim( $bg . ' ' . $fg . ' ' . $extra ) ); ?>" id="<?= esc_attr( $section_id ); ?>">
	<div class="container">
		<?php
		// Skip the header row entirely when empty, so it can't leave a gap.
		if ( $eyebrow || $headline || $intro ) {
			?>
		<div class="downloads-header row g-4 align-items-end">
			<?php
			if ( $eyebrow || $headline ) {
				?>
			<div class="col-lg-7">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="downloads-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
				<?php
			}
			if ( $intro ) {
				?>
			<div class="col-lg-5">
				<div class="downloads-intro prose-md"><?= wp_kses( $intro, $br_allowed ); ?></div>
			</div>
				<?php
			}
			?>
		</div>
			<?php
		}
		?>

		<ul class="downloads-grid">
			<?php
			foreach ( $items as $item ) {
				$file  = isset( $item['file'] ) ? $item['file'] : null;
				$title = isset( $item['title'] ) ? $item['title'] : '';
				$meta  = isset( $item['meta'] ) ? $item['meta'] : '';

				if ( empty( $file ) || empty( $file['url'] ) ) {
					continue;
				}

				// Fall back to the attachment title when the editor leaves it blank.
				if ( '' === $title ) {
					$title = ! empty( $file['title'] ) ? $file['title'] : basename( $file['url'] );
				}

				$ext  = strtoupper( pathinfo( $file['url'], PATHINFO_EXTENSION ) );
				$size = ! empty( $file['filesize'] ) ? size_format( $file['filesize'] ) : '';
				$spec = array_filter( array( $ext, $size ) );

				// PDFs get a page-one cover image once Imagick has generated one;
				// anything without a preview falls back to the extension badge.
				$cover = '';

				if ( ! empty( $file['ID'] ) ) {
					$cover = wp_get_attachment_image(
						$file['ID'],
						'thumbnail',
						false,
						array(
							'class'   => 'downloads-item-cover',
							'alt'     => '',
							'loading' => 'lazy',
						)
					);
				}
				?>
			<li class="downloads-item">
				<a class="downloads-link" href="<?= esc_url( $file['url'] ); ?>" download>
					<span class="downloads-item-icon<?= $cover ? ' downloads-item-icon--cover' : ''; ?>" aria-hidden="true">
						<?php
						if ( $cover ) {
							echo wp_kses_post( $cover );
						} elseif ( $ext ) {
							?>
						<span class="downloads-item-ext"><?= esc_html( $ext ); ?></span>
							<?php
						}
						?>
					</span>
					<span class="downloads-item-text">
						<span class="downloads-item-title"><?= esc_html( $title ); ?></span>
						<?php
						if ( $meta ) {
							?>
						<span class="downloads-item-meta"><?= esc_html( $meta ); ?></span>
							<?php
						}
						if ( $spec ) {
							?>
						<span class="downloads-item-spec"><?= esc_html( implode( ' · ', $spec ) ); ?></span>
							<?php
						}
						?>
					</span>
					<span class="downloads-item-arrow" aria-hidden="true"></span>
					<span class="visually-hidden"><?= esc_html__( 'Download', 'cb-hts2026' ); ?></span>
				</a>
			</li>
				<?php
			}
			?>
		</ul>
	</div>
</section>
