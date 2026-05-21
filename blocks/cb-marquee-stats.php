<?php
/**
 * Block template for CB Marquee Stats.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! have_rows( 'items' ) ) {
	return;
}

$classes = 'marquee';
if ( ! empty( $block['className'] ) ) {
	$classes .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes .= ' align' . $block['align'];
}
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<div class="<?= esc_attr( $classes ); ?>" data-marquee aria-hidden="true"<?= wp_kses_post( $anchor ); ?>>
	<div class="marquee-track" data-marquee-track>
		<?php
		while ( have_rows( 'items' ) ) {
			the_row();
			$stat = get_sub_field( 'stat' );
			$item = get_sub_field( 'title' );
			if ( ! $stat && ! $item ) {
				continue;
			}
			?>
			<span class="marquee-item">
				<?php
				if ( $stat ) {
					?>
				<span class="marquee-num"><?= esc_html( $stat ); ?></span>
					<?php
				}
				if ( $item ) {
					?>
				<span class="marquee-title"><?= esc_html( $item ); ?></span>
					<?php
				}
				?>
			</span>
			<?php
		}
		?>
	</div>
</div>

<?php
// Print the marquee JS once per page, regardless of how many marquee blocks exist.
cb_marquee_script();
