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
<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="footer-top row g-4 g-lg-5">
            <div class="col-12 col-lg-5">
                <img src="<?= esc_url( get_stylesheet_directory_uri() . '/img/HTS_Logo_White.png' ); ?>" alt="HTS Industries" class="footer-logo-img">
                <p class="footer-tagline">Premium temporary and semi-permanent modular structures for industry and commerce. A division of HTS-Tentiq — German-engineered, UK-installed since 2002.</p>
                <div class="footer-social">
                    <a href="#" class="social-btn" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-4 0v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg></a>
                    <a href="#" class="social-btn" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg></a>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="footer-col-title">Products</div>
                <ul class="footer-links">
                    <li><a href="#products">Industrial Canopies</a></li>
                    <li><a href="#products">Non-Insulated Buildings</a></li>
                    <li><a href="#products">Insulated Buildings</a></li>
                    <li><a href="#products">Sports Halls</a></li>
                    <li><a href="#products">Manhattan Structures</a></li>
                    <li><a href="#products">Custom Buildings</a></li>
                    <li><a href="#configurator">3D Configurator</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="footer-col-title">Applications</div>
                <ul class="footer-links">
                    <li><a href="#applications">Logistics &amp; Loading</a></li>
                    <li><a href="#applications">Warehousing</a></li>
                    <li><a href="#applications">Manufacturing</a></li>
                    <li><a href="#applications">Waste &amp; Recycling</a></li>
                    <li><a href="#applications">Sports &amp; Leisure</a></li>
                    <li><a href="#applications">Hospitality &amp; Retail</a></li>
                    <li><a href="#applications">Film &amp; TV</a></li>
                </ul>
            </div>
            <div class="col-12 col-md-4 col-lg-3">
                <div class="footer-col-title">Get in touch</div>
                <div class="footer-contact-item">
                    <div class="label">Phone</div>
					<a href="tel:<?= esc_attr( parse_phone( get_field( 'contact_phone', 'option' ) ) ); ?>"><?= esc_html( get_field( 'contact_phone', 'option' ) ); ?></a>
                </div>
                <div class="footer-contact-item">
                    <div class="label">Email</div>
					<a href="mailto:<?= esc_attr( antispambot( get_field( 'contact_email', 'option' ) ) ); ?>"><?= esc_html( antispambot( get_field( 'contact_email', 'option' ) ) ); ?></a>
                </div>
                <a href="#contact" class="btn btn-primary footer-cta">Request a quote</a>
            </div>
        </div>
        <div class="footer-bottom d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="footer-bottom-left">© 2026 HTS Industries Ltd. A division of HTS-Tentiq.</div>
            <div class="footer-bottom-right d-flex flex-column flex-sm-row gap-2 gap-sm-4">
                <a href="#">Privacy Policy</a>
                <a href="#">Cookie Policy</a>
                <a href="#">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
