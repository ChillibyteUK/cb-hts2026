<?php
/**
 * Breadcrumb bar.
 *
 * Renders the trail from cb_get_breadcrumb_trail(), or an explicit
 * `$args['trail']` of `label` / `url` pairs. The final entry is always plain
 * text. Marked up as a schema.org BreadcrumbList.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$crumb = wp_parse_args(
	$args ?? array(),
	array( 'trail' => array() )
);

$trail = ! empty( $crumb['trail'] ) ? $crumb['trail'] : cb_get_breadcrumb_trail();

if ( count( $trail ) < 2 ) {
	return;
}

$last = count( $trail ) - 1;
?>
<div class="crumb">
	<div class="container">
		<nav class="crumb-inner" aria-label="Breadcrumb">
			<ol class="crumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
				<?php
				foreach ( $trail as $index => $item ) {
					if ( empty( $item['label'] ) ) {
						continue;
					}

					$is_last = $index === $last;
					?>
				<li class="crumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
					<?php
					if ( ! empty( $item['url'] ) && ! $is_last ) {
						?>
					<a href="<?= esc_url( $item['url'] ); ?>" itemprop="item">
						<span itemprop="name"><?= wp_kses( $item['label'], array() ); ?></span>
					</a>
						<?php
					} else {
						?>
					<span class="<?= $is_last ? 'crumb-current' : ''; ?>" itemprop="name"><?= wp_kses( $item['label'], array() ); ?></span>
						<?php
					}
					?>
					<meta itemprop="position" content="<?= esc_attr( (string) ( $index + 1 ) ); ?>">
				</li>
					<?php
				}
				?>
			</ol>
		</nav>
	</div>
</div>
