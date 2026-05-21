<?php
/**
 * Block template for CB Projects Grid.
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

$projects = new WP_Query(
	array(
		'post_type'      => 'project',
		'posts_per_page' => 5,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section class="projects" id="projects">
	<div class="container">
		<div class="projects-header row align-items-end g-4 g-lg-5">
			<div class="col-lg-8">
				<?php
				if ( $eyebrow ) {
					?>
				<div class="eyebrow"><?= esc_html( $eyebrow ); ?></div>
					<?php
				}
				if ( $headline ) {
					?>
				<h2 class="projects-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
		</div>

		<?php
		if ( $projects->have_posts() ) {
			?>
		<div class="projects-mosaic">
			<?php
			while ( $projects->have_posts() ) {
				$projects->the_post();
				$the_post_id = get_the_ID();
				$has_url     = (bool) trim( get_the_content() );
				$el          = $has_url ? 'a' : 'div';
				$attrs       = $has_url ? ' href="' . esc_url( get_permalink() ) . '"' : '';
				$classes     = 'proj-card';

				if ( $has_url ) {
					$classes .= ' proj-card--linked';
				}

				if ( 0 === $projects->current_post ) {
					$classes .= ' proj-card--hero';
				} elseif ( 3 === $projects->current_post ) {
					$classes .= ' proj-card--wide';
				} else {
					$classes .= ' proj-card--std';
				}

				$terms = get_the_terms( $the_post_id, 'application_cat' );
				$aterm = ! empty( $terms ) && ! is_wp_error( $terms ) ? reset( $terms ) : null;
				?>
				<<?= esc_attr( $el ); ?> class="<?= esc_attr( $classes ); ?>"<?= wp_kses_post( $attrs ); ?>>
					<?php
					if ( has_post_thumbnail() ) {
						echo get_the_post_thumbnail(
							$the_post_id,
							'large',
							array(
								'class' => 'proj-card-img',
								'alt'   => esc_attr( get_the_title() ),
							)
						);
					}
					?>
					<div class="proj-card-overlay"></div>
					<div class="proj-card-body">
						<?php
						if ( $aterm ) {
							?>
						<div class="proj-card-meta"><?= esc_html( $aterm->name ); ?></div>
							<?php
						}
						?>
						<div class="proj-card-title"><?= esc_html( get_the_title() ); ?></div>
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
