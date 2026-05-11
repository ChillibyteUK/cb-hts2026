<?php
/**
 * Footer template for the Identity Coda 2026 theme.
 *
 * This file contains the footer section of the theme, including navigation menus,
 * office addresses, and colophon information.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;
?>
<div id="footer-top"></div>

<footer class="footer pt-5 pb-4">
    <div class="container">
        <div class="row pb-4 g-4">
			<div class="col-12 col-md-2">
				<img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/sis-logo-wo.png' ); ?>" alt="Strategic Insurance Services logo" class="footer__logo">
			</div>
			<div class="col-12 col-md-4">
				<div class="mb-4">
					Strategic Insurance Services Limited<br>
					Shoreham-by-Sea<br>
					United Kingdom
				</div>
				<div>
					T: <a href="tel:<?= esc_attr( parse_phone( get_field( 'contact_phone', 'option' ) ) ); ?>" class="footer__contact"><?= esc_html( get_field( 'contact_phone', 'option' ) ); ?></a><br>
					E: <a href="mailto:<?= esc_attr( antispambot( get_field( 'contact_email', 'option' ) ) ); ?>" class="footer__contact"><?= esc_html( antispambot( get_field( 'contact_email', 'option' ) ) ); ?></a><br>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
					<a href="<?= esc_url( get_field( 'linkedin_url', 'option' ) ); ?>" target="_blank" class="footer__contact">Find us on LinkedIn</a>
				</div>
			</div>
			<div class="col-12 col-sm-6 col-md-3">
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_services',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
            <div class="col-12 col-sm-6 col-md-3">
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_about',
						'menu_class'     => 'footer__menu mb-4',
					)
				);
				?>
				<?=
				wp_nav_menu(
					array(
						'theme_location' => 'footer_menu_legal',
						'menu_class'     => 'footer__menu',
					)
				);
				?>
			</div>
		</div>
	</div>

	<div class="container pt-4 footer__colophon">
		&copy; <?= esc_html( gmdate( 'Y' ) ); ?> Strategic Insurance Services Limited<br>
		<?= wp_kses_post( get_field( 'colophon', 'option' ) ); ?>
	</div>
</footer>
<?php wp_footer(); ?>
</body>

</html>