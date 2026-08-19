<?php
/**
 * Block template for CB Related Projects.
 *
 * Three-up card grid of case studies. Either hand-picked, or auto-selected by
 * shared project category, excluding the post currently being viewed.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$headline = get_field( 'headline' );
$manual   = get_field( 'projects' );
$view_all = get_field( 'link' );

$projects = cb_get_related_projects( $manual, 3 );

if ( empty( $projects ) ) {
	return;
}

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$classes = array( 'related-projects' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

$anchor = ! empty( $block['anchor'] ) ? $block['anchor'] : 'related-projects';
?>
<section class="<?= esc_attr( implode( ' ', $classes ) ); ?>" id="<?= esc_attr( $anchor ); ?>">
	<div class="container">
		<div class="related-projects-header row align-items-end g-4">
			<div class="col-lg-8">
				<?php
				if ( $headline ) {
					?>
				<h2 class="related-projects-headline h3"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
					<?php
				}
				?>
			</div>
			<?php
			if ( ! empty( $view_all['url'] ) ) {
				?>
			<div class="col-lg-4 text-lg-end">
				<a href="<?= esc_url( $view_all['url'] ); ?>"
					<?= ! empty( $view_all['target'] ) ? ' target="' . esc_attr( $view_all['target'] ) . '" rel="noopener"' : ''; ?>
					class="btn btn-outline-dark">
					<?= esc_html( ! empty( $view_all['title'] ) ? $view_all['title'] : 'View all case studies' ); ?>
				</a>
			</div>
				<?php
			}
			?>
		</div>

		<div class="related-projects-grid">
			<?php
			foreach ( $projects as $project ) {
				$project_id = $project->ID;
				$category   = cb_project_category_name( $project_id );
				$summary    = get_the_excerpt( $project_id );
				?>
			<a class="related-project-card" href="<?= esc_url( get_permalink( $project_id ) ); ?>">
				<?php
				if ( has_post_thumbnail( $project_id ) ) {
					echo get_the_post_thumbnail(
						$project_id,
						'medium_large',
						array(
							'class'   => 'related-project-card-img',
							'loading' => 'lazy',
							'alt'     => esc_attr( get_the_title( $project_id ) ),
						)
					);
				}
				?>
				<div class="related-project-card-body">
					<?php
					if ( $category ) {
						?>
					<div class="related-project-card-meta"><?= esc_html( $category ); ?></div>
						<?php
					}
					?>
					<h3 class="related-project-card-title"><?= esc_html( get_the_title( $project_id ) ); ?></h3>
					<?php
					if ( $summary ) {
						?>
					<p class="related-project-card-summary"><?= esc_html( wp_trim_words( $summary, 22 ) ); ?></p>
						<?php
					}
					?>
				</div>
			</a>
				<?php
			}
			?>
		</div>
	</div>
</section>
