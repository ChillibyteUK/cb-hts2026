<?php
/**
 * Block template for CB Case Study Sidebar.
 *
 * A narrow column of project facts. Place it immediately before the run of
 * CB Case Study Section blocks — it floats left so those sections sit beside it,
 * and the next full-width band clears it.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$items = get_field( 'items' );
$cta   = get_field( 'cta' );

if ( empty( $items ) && empty( $cta['url'] ) ) {
	return;
}

$classes = array( 'cs-sidebar' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<aside class="<?= esc_attr( implode( ' ', $classes ) ); ?>"<?= wp_kses_post( $anchor ); ?>>
	<div class="cs-sidebar-inner">
		<?php
		if ( $items ) {
			foreach ( $items as $item ) {
				if ( empty( $item['label'] ) && empty( $item['value'] ) && empty( $item['link']['url'] ) ) {
					continue;
				}
				?>
		<div class="cs-sidebar-block">
				<?php
				if ( ! empty( $item['label'] ) ) {
					?>
			<div class="cs-sidebar-label"><?= esc_html( $item['label'] ); ?></div>
					<?php
				}
				?>
			<div class="cs-sidebar-value">
				<?php
				if ( ! empty( $item['link']['url'] ) ) {
					?>
				<a href="<?= esc_url( $item['link']['url'] ); ?>"
					<?= ! empty( $item['link']['target'] ) ? ' target="' . esc_attr( $item['link']['target'] ) . '" rel="noopener"' : ''; ?>>
					<?= esc_html( ! empty( $item['link']['title'] ) ? $item['link']['title'] : $item['value'] ); ?>
				</a>
					<?php
				} elseif ( ! empty( $item['value'] ) ) {
					echo wp_kses( $item['value'], array( 'br' => array() ) );
				}
				?>
			</div>
		</div>
				<?php
			}
		}

		if ( ! empty( $cta['url'] ) ) {
			?>
		<a href="<?= esc_url( $cta['url'] ); ?>"
			<?= ! empty( $cta['target'] ) ? ' target="' . esc_attr( $cta['target'] ) . '" rel="noopener"' : ''; ?>
			class="btn btn-primary cs-sidebar-cta">
			<?= esc_html( ! empty( $cta['title'] ) ? $cta['title'] : 'Discuss a similar project' ); ?>
		</a>
			<?php
		}
		?>
	</div>
</aside>
