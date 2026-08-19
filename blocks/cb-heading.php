<?php
/**
 * Block template for CB Heading.
 *
 * A heading that accepts the theme's <span> accent, which core/heading strips.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );

if ( ! $heading ) {
	return;
}

$level   = get_field( 'level' ) ? get_field( 'level' ) : 'h2';
$size    = get_field( 'size' ) ? get_field( 'size' ) : 'h3';
$classes = trim( 'cb-heading ' . $size . ' ' . ( $block['className'] ?? '' ) );
$anchor  = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';

printf(
	'<%1$s class="%2$s"%3$s>%4$s</%1$s>',
	esc_attr( $level ),
	esc_attr( $classes ),
	wp_kses_post( $anchor ),
	wp_kses(
		$heading,
		array(
			'span' => array(),
			'br'   => array(),
		)
	)
);
