<?php
/**
 * Template for a single product.
 *
 * Products are composed entirely of blocks, same as a page — this adds the
 * breadcrumb bar.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

get_header();

the_post();

get_template_part( 'template-parts/breadcrumb' );
?>
<main id="main" class="product">
	<?php the_content(); ?>
</main>
<?php
get_footer();
