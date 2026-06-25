<?php
/**
 * Block template for CB FAQs.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'cb_faqs_add_schema_items' ) ) {
	/**
	 * Collect FAQ items and output a single FAQPage schema in wp_footer.
	 *
	 * @param array $items Array of items with 'question' and 'answer' keys.
	 * @return void
	 */
	function cb_faqs_add_schema_items( array $items ) {
		static $all_items = array();
		static $hooked    = false;

		foreach ( $items as $item ) {
			$all_items[] = $item;
		}

		if ( ! $hooked ) {
			$hooked = true;
			add_action(
				'wp_footer',
				function () use ( &$all_items ) {
					if ( empty( $all_items ) ) {
						return;
					}

					$entities = array_map(
						function ( $item ) {
							return array(
								'@type'          => 'Question',
								'name'           => $item['question'],
								'acceptedAnswer' => array(
									'@type' => 'Answer',
									'text'  => $item['answer'],
								),
							);
						},
						$all_items
					);

					$schema = array(
						'@context'   => 'https://schema.org',
						'@type'      => 'FAQPage',
						'mainEntity' => $entities,
					);

					echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
				}
			);
		}
	}
}

$headline = get_field( 'title' );
$intro    = get_field( 'intro' );
$faqs     = get_field( 'faqs' );

if ( empty( $faqs ) || ! is_array( $faqs ) ) {
	return;
}

$block_faq_items = array();

foreach ( $faqs as $faq ) {
	$question = isset( $faq['question'] ) ? wp_strip_all_tags( $faq['question'] ) : '';
	$answer   = isset( $faq['answer'] ) ? wp_strip_all_tags( $faq['answer'] ) : '';

	if ( '' === $question || '' === $answer ) {
		continue;
	}

	$block_faq_items[] = array(
		'question' => $question,
		'answer'   => $answer,
	);
}

cb_faqs_add_schema_items( $block_faq_items );

$section_id = $block['anchor'] ?? ( $block['id'] ?? wp_unique_id( 'cb-faqs-' ) );
$extra      = $block['className'] ?? '';
$bg         = ! empty( $block['backgroundColor'] ) ? 'has-' . $block['backgroundColor'] . '-background-color' : '';
$fg         = ! empty( $block['textColor'] ) ? 'has-' . $block['textColor'] . '-color' : '';
$line_class = 'dark-lines';

if ( ! empty( $block['backgroundColor'] ) ) {
	if ( preg_match( '/(\d+)(?!.*\d)/', $block['backgroundColor'], $matches ) ) {
		$line_class = (int) $matches[1] >= 600 ? 'light-lines' : 'dark-lines';
	} else {
		$line_class = 'light-lines';
	}
}

$headline_allowed = array(
	'span' => array(),
	'br'   => array(),
);

$br_allowed = array(
	'br' => array(),
);

$accordion_id = wp_unique_id( 'faq-accordion-' );
?>
<section class="faq <?= esc_attr( trim( $bg . ' ' . $fg . ' ' . $line_class . ' ' . $extra ) ); ?>" id="<?= esc_attr( $section_id ); ?>">
	<div class="container">
		<div class="faq-header">
			<div class="eyebrow eyebrow--plain center">FAQs</div>
			<?php if ( $headline ) : ?>
			<h2 class="faq-headline h2"><?= wp_kses( $headline, $headline_allowed ); ?></h2>
			<?php endif; ?>
			<?php if ( $intro ) : ?>
			<p class="faq-sub"><?= wp_kses( $intro, $br_allowed ); ?></p>
			<?php endif; ?>
		</div>
		<div class="accordion accordion-flush" id="<?= esc_attr( $accordion_id ); ?>">
			<?php
			foreach ( $faqs as $index => $faq ) {
				$question    = $faq['question'] ?? '';
				$answer      = $faq['answer'] ?? '';
				$collapse_id = wp_unique_id( 'faq-collapse-' );

				if ( '' === trim( wp_strip_all_tags( $question ) ) && '' === trim( wp_strip_all_tags( $answer ) ) ) {
					continue;
				}
				?>
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button<?= 0 !== $index ? ' collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?= esc_attr( $collapse_id ); ?>" aria-expanded="<?= 0 === $index ? 'true' : 'false'; ?>" aria-controls="<?= esc_attr( $collapse_id ); ?>">
						<?= esc_html( $question ); ?>
					</button>
				</h2>
				<div id="<?= esc_attr( $collapse_id ); ?>" class="accordion-collapse collapse<?= 0 === $index ? ' show' : ''; ?>" data-bs-parent="#<?= esc_attr( $accordion_id ); ?>">
					<div class="accordion-body"><?= wp_kses( $answer, $br_allowed ); ?></div>
				</div>
			</div>
				<?php
			}
			?>
		</div>
	</div>
</section>
