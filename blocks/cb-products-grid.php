<?php
/**
 * Block template for CB Products Grid.
 *
 * Header from ACF + cards pulled from the `product` CPT.
 * Per-product fields (tag, card_intro) live on each product post.
 * Cards become links + show the "Specification" CTA only when the
 * product post has body content.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$eyebrow  = get_field( 'eyebrow' );
$headline = get_field( 'headline' );

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$products = new WP_Query(
	array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section class="products" id="products">
	<div class="container">
		<div class="products-header row align-items-end g-4 g-lg-5">
			<div class="col-lg-8">
				<?php if ( $eyebrow ) { ?>
					<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
				<?php } ?>
				<?php if ( $headline ) { ?>
					<h2 class="products-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php } ?>
			</div>
		</div>

		<?php
		if ( $products->have_posts() ) {
			?>
			<div class="product-grid">
				<?php
				$i = 0;
				while ( $products->have_posts() ) {
					$products->the_post();
					++$i;
					$the_post_id = get_the_ID();
					$num         = str_pad( (string) $i, 2, '0', STR_PAD_LEFT );
					$card_tag    = get_field( 'tag', $the_post_id );
					$card_intro  = get_field( 'card_intro', $the_post_id );
					$has_url     = (bool) trim( get_the_content() );
					$el          = $has_url ? 'a' : 'div';
					$classes     = 'product-card';
					if ( $has_url ) {
						$classes .= ' product-card--linked';
					}
					?>
					<<?= esc_attr( $el ); ?> class="<?= esc_attr( $classes ); ?>"
					<?php
					if ( $has_url ) {
						?>
						href="<?= esc_url( get_permalink() ); ?>"
						<?php
					}
					?>
					>
						<div class="product-card-img-wrap">
							<?php
							if ( $card_tag ) {
								?>
							<span class="product-card-tag"><?= esc_html( $card_tag ); ?></span>
								<?php
							}
							?>
							<span class="product-card-num"><?= esc_html( $num ); ?></span>
							<?php
							if ( has_post_thumbnail() ) {
								echo get_the_post_thumbnail(
									$the_post_id,
									'large',
									array(
										'class' => 'product-card-img',
										'alt'   => esc_attr( get_the_title() ),
									)
								);
							}
							?>
						</div>
						<div class="product-card-body">
							<div class="product-card-title"><?= esc_html( get_the_title() ); ?></div>
							<?php
							if ( $card_intro ) {
								?>
							<div class="product-card-desc"><?= esc_html( $card_intro ); ?></div>
								<?php
							}
							if ( $has_url ) {
								?>
							<span class="product-card-link">
								View Product
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
									<path d="M5 12h14M12 5l7 7-7 7"/>
								</svg>
							</span>
								<?php
							}
							?>
						</div>
					</<?= esc_attr( $el ); ?>>
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
