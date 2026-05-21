<?php
/**
 * Block template for CB Applications Grid.
 *
 * Header from ACF + cards pulled from the `application` CPT.
 * Each card links to the post if the post has body content;
 * otherwise it renders as a non-interactive tile.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );
$lede     = get_field( 'lede' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$apps = new WP_Query(
	array(
		'post_type'      => 'application',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section class="apps" id="applications">
	<div class="container">
		<div class="apps-header row align-items-end g-4 g-lg-5">
			<div class="col-lg-5">
				<?php if ( $eyebrow ) { ?>
					<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
				<?php } ?>
				<?php if ( $headline ) { ?>
					<h2 class="apps-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php } ?>
			</div>
			<?php if ( $lede ) { ?>
				<div class="col-lg-7">
					<div class="apps-intro prose-md"><?= wp_kses_post( $lede ); ?></div>
				</div>
			<?php } ?>
		</div>

		<?php
		if ( $apps->have_posts() ) {
			?>
			<div class="apps-grid">
				<?php
				$i = 0;
				while ( $apps->have_posts() ) {
					$apps->the_post();
					++$i;
					$num      = str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
					$has_url  = (bool) trim( get_the_content() );
					$html_tag = $has_url ? 'a' : 'div';
					$attrs    = $has_url ? ' href="' . esc_url( get_permalink() ) . '"' : '';
					$classes  = 'app-card';
					if ( $has_url ) {
						$classes .= ' app-card--linked';
					}
					?>
					<<?= esc_attr( $html_tag ); ?> class="<?= esc_attr( $classes ); ?>"<?= wp_kses_post( $attrs ); ?>>
						<?php if ( has_post_thumbnail() ) { ?>
							<?php
							echo get_the_post_thumbnail(
								get_the_ID(),
								'large',
								array(
									'class' => 'app-card-img',
									'alt'   => esc_attr( get_the_title() ),
								)
							);
							?>
						<?php } ?>
						<div class="app-card-overlay" aria-hidden="true"></div>
						<div class="app-card-num"><?= esc_html( $num ); ?></div>
						<?php
						if ( $has_url ) {
							?>
							<div class="app-card-arrow" aria-hidden="true">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
									<path d="M7 17L17 7M7 7h10v10"/>
								</svg>
							</div>
							<?php
						}
						?>
						<div class="app-card-body">
							<div class="app-card-title"><?= esc_html( get_the_title() ); ?></div>
						</div>
					</<?= esc_attr( $html_tag ); ?>>
					<?php
				}
				wp_reset_postdata();
				?>
			</div>
			<?php
		}
		?>
	</div>
</section>
