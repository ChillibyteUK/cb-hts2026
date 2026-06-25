<?php
/**
 * Block template for CB Products Nav.
 *
 * Displays a swiper slider of product cards, excluding the current product
 * when viewed on a single product page.
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

$exclude = is_singular( 'product' ) ? array( get_the_ID() ) : array();

$products = new WP_Query(
	array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
		'post__not_in'   => $exclude,
	)
);

if ( ! $products->have_posts() ) {
	return;
}

// Collect card data so we can duplicate slides for smooth Swiper looping.
$cards = array();
while ( $products->have_posts() ) {
	$products->the_post();
	$ppost_id = get_the_ID();
	$cards[]  = array(
		'id'        => $ppost_id,
		'title'     => get_the_title(),
		'permalink' => get_permalink(),
		'thumbnail' => get_post_thumbnail_id() ?
			get_the_post_thumbnail(
				$ppost_id,
				'large',
				array(
					'class' => 'product-card-img',
					'alt'   => esc_attr( get_the_title() ),
				)
			) : '',
		'tag'       => get_field( 'tag', $ppost_id ),
		'intro'     => get_field( 'card_intro', $ppost_id ),
	);
}
wp_reset_postdata();

// Duplicate cards so Swiper has ≥9 slides for smooth looping.
$slide_count = count( $cards );
if ( $slide_count < 9 && $slide_count > 0 ) {
	$cards = array_merge( ...array_fill( 0, (int) ceil( 9 / $slide_count ), $cards ) );
}

$block_id   = $block['anchor'] ?? ( $block['id'] ?? wp_unique_id( 'cb-products-nav-' ) );
$extra      = $block['className'] ?? '';
$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$line_class = 'dark-lines';

if ( ! empty( $block['backgroundColor'] ) ) {
	if ( preg_match( '/(\d+)(?!.*\d)/', $block['backgroundColor'], $matches ) ) {
		$line_class = (int) $matches[1] >= 600 ? 'light-lines' : 'dark-lines';
	} else {
		$line_class = 'light-lines';
	}
}
?>
<section class="products-nav <?= esc_attr( trim( $bg . ' ' . $fg . ' ' . $line_class . ' ' . $extra ) ); ?>" id="<?= esc_attr( $block_id ); ?>">
	<div class="container">
		<?php
		if ( $eyebrow || $headline ) {
			?>
		<div class="products-nav-header">
			<?php
			if ( $eyebrow ) {
				?>
			<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
				<?php
			}
			if ( $headline ) {
				?>
			<h2 class="products-nav-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
				<?php
			}
			?>
		</div>
			<?php
		}
		?>
		<div class="swiper products-nav-swiper">
			<div class="swiper-wrapper">
				<?php
				foreach ( $cards as $card ) {
					?>
				<div class="swiper-slide">
					<a class="product-card product-card--linked" href="<?= esc_url( $card['permalink'] ); ?>">
						<div class="product-card-img-wrap">
							<?php
							if ( $card['tag'] ) {
								?>
							<span class="product-card-tag"><?= esc_html( $card['tag'] ); ?></span>
								<?php
							}
							?>
							<?= $card['thumbnail']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<div class="product-card-body">
							<div class="product-card-title"><?= esc_html( $card['title'] ); ?></div>
							<?php
							if ( $card['intro'] ) {
								?>
							<div class="product-card-desc"><?= esc_html( $card['intro'] ); ?></div>
								<?php
							}
							?>
							<span class="product-card-link">
								View Product
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true">
									<path d="M5 12h14M12 5l7 7-7 7"/>
								</svg>
							</span>
						</div>
					</a>
				</div>
					<?php
				}
				?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
		</div>
	</div>
</section>

<?php
wp_add_inline_script(
	'swiper',
	'var swiper = new Swiper("#' . $block_id . ' .swiper", {
		slidesPerView: 3,
		slidesPerGroup: 1,
		spaceBetween: 20,
		loop: true,
		loopAdditionalSlides: 3,
		watchSlidesProgress: true,
		speed: 600,
		autoplay: {
			delay: 5000,
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: "#' . $block_id . ' .swiper-button-next",
			prevEl: "#' . $block_id . ' .swiper-button-prev",
		},
	});'
);
?>
