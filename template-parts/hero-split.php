<?php
/**
 * Split hero: copy on the left, image with stat badge on the right.
 *
 * Shared by the CB Product Hero block and the single project template. Layout
 * classes live in `_cb_home_hero.scss` / `_cb_product_hero.scss`.
 *
 * Expected $args keys — all optional unless noted:
 * - id            string  Section id attribute.
 * - classes       string  Extra classes appended to `.hero`.
 * - tag           string  Small pill above the title.
 * - h1            string  Heading markup; `span` is allowed for the accent.
 * - subtitle      string  Single line under the heading.
 * - lede          string  Rich text.
 * - bullets       string  Newline-delimited list.
 * - image_id      int     Attachment ID for the hero image.
 * - badge_number  string
 * - badge_suffix  string
 * - badge_label   string  Newlines already converted to `br`.
 * - cta_primary   array   ACF link array.
 * - cta_secondary array   ACF link array.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$hero = wp_parse_args(
	$args ?? array(),
	array(
		'id'            => '',
		'classes'       => '',
		'tag'           => '',
		'h1'            => '',
		'subtitle'      => '',
		'lede'          => '',
		'bullets'       => '',
		'image_id'      => 0,
		'badge_number'  => '',
		'badge_suffix'  => '',
		'badge_label'   => '',
		'cta_primary'   => array(),
		'cta_secondary' => array(),
	)
);

$hero_classes = trim( 'hero ' . $hero['classes'] );
?>
<section <?= $hero['id'] ? 'id="' . esc_attr( $hero['id'] ) . '" ' : ''; ?>class="<?= esc_attr( $hero_classes ); ?>">
	<div class="container">
		<div class="hero-split">
			<div class="hero-content">
				<?php
				if ( $hero['tag'] ) {
					?>
				<div class="hero-tag"><?= esc_html( $hero['tag'] ); ?></div>
					<?php
				}

				if ( $hero['h1'] ) {
					?>
				<h1 class="hero-h1">
					<?=
					wp_kses(
						$hero['h1'],
						array(
							'span' => array(),
							'br'   => array(),
						)
					);
					?>
				</h1>
					<?php
				}

				if ( $hero['subtitle'] ) {
					?>
				<p class="hero-sub">
					<?=
					wp_kses(
						$hero['subtitle'],
						array(
							'strong' => array(),
							'em'     => array(),
						)
					);
					?>
				</p>
					<?php
				}

				if ( $hero['lede'] ) {
					?>
				<div class="hero-lede"><?= wp_kses_post( $hero['lede'] ); ?></div>
					<?php
				}

				if ( $hero['bullets'] ) {
					?>
				<ul class="hero-bullets">
					<?= wp_kses_post( cb_list( $hero['bullets'] ) ); ?>
				</ul>
					<?php
				}

				if ( ! empty( $hero['cta_primary']['url'] ) || ! empty( $hero['cta_secondary']['url'] ) ) {
					?>
				<div class="hero-actions">
					<?php
					if ( ! empty( $hero['cta_primary']['url'] ) ) {
						?>
					<a href="<?= esc_url( $hero['cta_primary']['url'] ); ?>"
						<?= ! empty( $hero['cta_primary']['target'] ) ? ' target="' . esc_attr( $hero['cta_primary']['target'] ) . '" rel="noopener"' : ''; ?>
						class="btn btn-primary">
						<?= esc_html( ! empty( $hero['cta_primary']['title'] ) ? $hero['cta_primary']['title'] : 'Get in touch' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
							<path d="M5 12h14M12 5l7 7-7 7"/>
						</svg>
					</a>
						<?php
					}

					if ( ! empty( $hero['cta_secondary']['url'] ) ) {
						?>
					<a href="<?= esc_url( $hero['cta_secondary']['url'] ); ?>"
						<?= ! empty( $hero['cta_secondary']['target'] ) ? ' target="' . esc_attr( $hero['cta_secondary']['target'] ) . '" rel="noopener"' : ''; ?>
						class="btn btn-outline-dark">
						<?= esc_html( ! empty( $hero['cta_secondary']['title'] ) ? $hero['cta_secondary']['title'] : 'Learn more' ); ?>
					</a>
						<?php
					}
					?>
				</div>
					<?php
				}
				?>
			</div>

			<div class="hero-visual">
				<?php
				if ( $hero['image_id'] ) {
					?>
				<div class="hero-img-wrap">
					<?= wp_get_attachment_image( (int) $hero['image_id'], 'full', false, array( 'class' => 'hero-img' ) ); ?>
				</div>
					<?php
				}

				if ( $hero['badge_number'] || $hero['badge_label'] ) {
					?>
				<div class="hero-badge">
					<div>
						<?php
						if ( $hero['badge_number'] ) {
							?>
						<div class="hero-badge-num">
							<?= esc_html( $hero['badge_number'] ); ?>
							<?php
							if ( $hero['badge_suffix'] ) {
								?>
							<sup><?= esc_html( $hero['badge_suffix'] ); ?></sup>
								<?php
							}
							?>
						</div>
							<?php
						}

						if ( $hero['badge_label'] ) {
							?>
						<div class="hero-badge-label"><?= wp_kses( $hero['badge_label'], array( 'br' => array() ) ); ?></div>
							<?php
						}
						?>
					</div>
				</div>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</section>
