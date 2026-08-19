<?php
/**
 * Template for a single project (case study).
 *
 * The page is composed entirely of blocks — nothing lives in post meta. A case
 * study is typically: CB Product Hero, CB Spec Bar, CB Case Study Body,
 * CB Text Stats (pull-quote variant), CB Client Projects Gallery (feature
 * layout), CB Product Used, CB Image CTA, CB Related Projects.
 *
 * The only thing this template adds over page.php is the breadcrumb bar.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

get_header();

the_post();

get_template_part( 'template-parts/breadcrumb' );
?>
<main id="main" class="case-study">
	<?php the_content(); ?>
</main>
<?php
get_footer();
