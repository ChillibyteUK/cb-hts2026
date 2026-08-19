<?php
/**
 * Per-post JSON-LD schema markup.
 *
 * Provides an ACF field on every public post type for hand-authored JSON-LD,
 * validates it on save, and emits it in wp_head after Yoast's own graph.
 *
 * Yoast retains ownership of WebPage, WebSite and BreadcrumbList. Its
 * Organization node is stood down only on pages where this field supplies a
 * replacement, so that its `publisher`/`about` @id references always resolve.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * The field name this whole file keys off.
 */
const CB_SCHEMA_FIELD = 'cb_schema_jsonld';

/**
 * Types Yoast still emits, which this field should not duplicate.
 */
const CB_SCHEMA_YOAST_OWNED_TYPES = array( 'WebPage', 'WebSite', 'BreadcrumbList' );

/**
 * Types that must carry the site's canonical Organization @id so that Yoast's
 * cross-references resolve. Organization subtypes (LocalBusiness, Corporation)
 * can be added here.
 */
const CB_SCHEMA_ORGANIZATION_TYPES = array( 'Organization' );

/**
 * Returns the canonical @id that an Organization node must use.
 *
 * Matches Yoast's Schema_IDs::ORGANIZATION_HASH appended to its trailing-slashed
 * site URL, so that its `publisher` and `about` references resolve to our node.
 *
 * @return string
 */
function cb_schema_organization_id() {
	return home_url( '/' ) . '#organization';
}

/**
 * Adds a location rule for every public post type missing from the field group.
 *
 * ACF has no wildcard for post types, so a new CPT would otherwise silently not
 * show the field.
 *
 * @param array $field_groups All loaded field groups.
 * @return array
 */
function cb_schema_extend_location_rules( $field_groups ) {
	foreach ( $field_groups as $index => $group ) {
		if ( ! isset( $group['key'] ) || 'group_cb_schema' !== $group['key'] ) {
			continue;
		}

		$location = isset( $group['location'] ) && is_array( $group['location'] ) ? $group['location'] : array();
		$covered  = array();

		foreach ( $location as $rule_group ) {
			foreach ( (array) $rule_group as $rule ) {
				if ( isset( $rule['param'], $rule['value'] ) && 'post_type' === $rule['param'] ) {
					$covered[] = $rule['value'];
				}
			}
		}

		$post_types = get_post_types(
			array(
				'public'  => true,
				'show_ui' => true,
			),
			'names'
		);
		unset( $post_types['attachment'] );

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $covered, true ) ) {
				continue;
			}

			$location[] = array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => $post_type,
				),
			);
		}

		$field_groups[ $index ]['location'] = $location;
	}

	return $field_groups;
}
add_filter( 'acf/load_field_groups', 'cb_schema_extend_location_rules', 30 );

/**
 * Flattens a decoded JSON-LD payload into a list of entity nodes.
 *
 * Handles a single object, a top-level array of objects, and an @graph wrapper.
 *
 * @param mixed $data Decoded JSON.
 * @return array List of nodes that are arrays.
 */
function cb_schema_collect_nodes( $data ) {
	if ( ! is_array( $data ) ) {
		return array();
	}

	if ( isset( $data['@graph'] ) && is_array( $data['@graph'] ) ) {
		$candidates = $data['@graph'];
	} elseif ( array_keys( $data ) === range( 0, count( $data ) - 1 ) ) {
		// Sequential keys: a top-level array of entities.
		$candidates = $data;
	} else {
		$candidates = array( $data );
	}

	return array_values( array_filter( $candidates, 'is_array' ) );
}

/**
 * Validates the JSON-LD field on save, blocking the save when malformed.
 *
 * @param bool|string $valid Current validity, or an error message.
 * @param mixed       $value The submitted value.
 * @param array       $field The field array.
 * @param string      $input The input name.
 * @return bool|string
 */
function cb_schema_validate_value( $valid, $value, $field, $input ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	if ( true !== $valid ) {
		return $valid;
	}

	// ACF hands validation the raw $_POST value, which WordPress has already run
	// through wp_magic_quotes(). Without unslashing, every quote in the JSON
	// arrives escaped and json_decode() fails on valid input.
	$value = is_string( $value ) ? trim( wp_unslash( $value ) ) : '';

	if ( '' === $value ) {
		return true;
	}

	$data = json_decode( $value, true );

	if ( JSON_ERROR_NONE !== json_last_error() ) {
		return 'Invalid JSON: ' . json_last_error_msg() . '.';
	}

	if ( ! is_array( $data ) ) {
		return 'Schema must be a JSON object, or an array of objects — not a single value.';
	}

	$nodes = cb_schema_collect_nodes( $data );

	if ( empty( $nodes ) ) {
		return 'No schema entities found. Expected at least one object with an @type.';
	}

	$has_top_level_context = isset( $data['@context'] );

	foreach ( $nodes as $position => $node ) {
		$label = count( $nodes ) > 1 ? sprintf( 'Entity %d', $position + 1 ) : 'Schema';

		if ( ! $has_top_level_context && ! isset( $node['@context'] ) ) {
			return sprintf( '%s is missing @context. Add "@context": "https://schema.org".', $label );
		}

		$context = isset( $node['@context'] ) ? $node['@context'] : $data['@context'];

		if ( is_string( $context ) && ! preg_match( '#^https?://schema\.org/?$#', trim( $context ) ) ) {
			return sprintf( '%s has an unexpected @context. It should be "https://schema.org".', $label );
		}

		if ( empty( $node['@type'] ) ) {
			return sprintf( '%s is missing @type.', $label );
		}

		$types    = array_map( 'strval', (array) $node['@type'] );
		$org_type = array_intersect( $types, CB_SCHEMA_ORGANIZATION_TYPES );

		if ( ! empty( $org_type ) ) {
			// Only the fragment is enforced. The host deliberately is not: the
			// same JSON has to save on local, staging and production, where
			// home_url() differs. A host mismatch is raised as a notice instead.
			$fragment = empty( $node['@id'] ) ? '' : (string) wp_parse_url( (string) $node['@id'], PHP_URL_FRAGMENT );

			if ( 'organization' !== $fragment ) {
				return sprintf(
					'%s is an %s, so its @id must end in "#organization" (e.g. "%s") — otherwise Yoast\'s publisher reference will not resolve to it.',
					$label,
					reset( $org_type ),
					cb_schema_organization_id()
				);
			}
		}
	}

	return true;
}
add_filter( 'acf/validate_value/name=' . CB_SCHEMA_FIELD, 'cb_schema_validate_value', 10, 4 );

/**
 * Warns after save when the field declares a type Yoast already emits.
 *
 * Non-blocking: overriding these is occasionally legitimate, so this surfaces a
 * notice rather than failing validation.
 *
 * @param int|string $post_id The ACF post ID.
 * @return void
 */
function cb_schema_warn_on_overlap( $post_id ) {
	if ( ! is_numeric( $post_id ) || ! function_exists( 'get_field' ) ) {
		return;
	}

	$value = get_field( CB_SCHEMA_FIELD, $post_id, false );

	if ( ! is_string( $value ) || '' === trim( $value ) ) {
		return;
	}

	$nodes    = cb_schema_collect_nodes( json_decode( $value, true ) );
	$overlaps = array();
	$messages = array();

	foreach ( $nodes as $node ) {
		if ( empty( $node['@type'] ) ) {
			continue;
		}

		$types = array_map( 'strval', (array) $node['@type'] );

		$overlaps = array_merge( $overlaps, array_intersect( $types, CB_SCHEMA_YOAST_OWNED_TYPES ) );

		// Flag an Organization @id pointing at a different host to this site.
		// Expected on local and staging; a genuine problem on production.
		if ( array_intersect( $types, CB_SCHEMA_ORGANIZATION_TYPES ) && ! empty( $node['@id'] ) ) {
			$id_host   = wp_parse_url( (string) $node['@id'], PHP_URL_HOST );
			$site_host = wp_parse_url( home_url( '/' ), PHP_URL_HOST );

			if ( $id_host && $site_host && $id_host !== $site_host ) {
				$messages[] = sprintf(
					'the Organization @id points at %1$s, but this site is %2$s. That is expected on local or staging; on production it must be %3$s or Yoast\'s publisher reference will not resolve to it.',
					$id_host,
					$site_host,
					cb_schema_organization_id()
				);
			}
		}
	}

	if ( ! empty( $overlaps ) ) {
		$messages[] = sprintf(
			'it declares %s, which Yoast already outputs. Duplicate entities can conflict unless they share the same @id.',
			implode( ', ', array_unique( $overlaps ) )
		);
	}

	if ( empty( $messages ) ) {
		return;
	}

	set_transient( 'cb_schema_notice_' . get_current_user_id(), array_values( array_unique( $messages ) ), 60 );
}
add_action( 'acf/save_post', 'cb_schema_warn_on_overlap', 20 );

/**
 * Renders the overlap warning stored by cb_schema_warn_on_overlap().
 *
 * @return void
 */
function cb_schema_admin_notice() {
	$key      = 'cb_schema_notice_' . get_current_user_id();
	$messages = get_transient( $key );

	if ( empty( $messages ) || ! is_array( $messages ) ) {
		return;
	}

	delete_transient( $key );

	foreach ( $messages as $message ) {
		printf(
			'<div class="notice notice-warning is-dismissible"><p><strong>Schema markup:</strong> %s</p></div>',
			esc_html( $message )
		);
	}
}
add_action( 'admin_notices', 'cb_schema_admin_notice' );

/**
 * Returns the decoded schema nodes for the current singular view.
 *
 * @return array List of entity nodes, empty when absent or malformed.
 */
function cb_schema_current_nodes() {
	static $cache = null;

	if ( null !== $cache ) {
		return $cache;
	}

	$cache = array();

	if ( ! is_singular() || ! function_exists( 'get_field' ) ) {
		return $cache;
	}

	$value = get_field( CB_SCHEMA_FIELD, get_queried_object_id(), false );

	if ( ! is_string( $value ) || '' === trim( $value ) ) {
		return $cache;
	}

	$data = json_decode( $value, true );

	if ( JSON_ERROR_NONE !== json_last_error() ) {
		return $cache;
	}

	$cache = cb_schema_collect_nodes( $data );

	return $cache;
}

/**
 * Whether the current page's schema field declares one of the given @types.
 *
 * @param array $types Schema.org type names.
 * @return bool
 */
function cb_schema_declares_type( array $types ) {
	foreach ( cb_schema_current_nodes() as $node ) {
		if ( empty( $node['@type'] ) ) {
			continue;
		}

		if ( array_intersect( array_map( 'strval', (array) $node['@type'] ), $types ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Stands down Yoast's Organization node only where this field replaces it.
 *
 * Elsewhere Yoast's node remains as the target for its own `publisher` and
 * `about` @id references.
 *
 * @param bool $needed Whether Yoast intends to output the piece.
 * @return bool
 */
function cb_schema_maybe_disable_yoast_organization( $needed ) {
	return cb_schema_declares_type( CB_SCHEMA_ORGANIZATION_TYPES ) ? false : $needed;
}
add_filter( 'wpseo_schema_needs_organization', 'cb_schema_maybe_disable_yoast_organization' );

/**
 * Outputs the page's JSON-LD in wp_head, after Yoast's graph.
 *
 * The stored string is decoded and re-encoded rather than echoed, so the output
 * is always well-formed and JSON_HEX_TAG makes a </script> breakout impossible.
 *
 * @return void
 */
function cb_schema_render() {
	$nodes = cb_schema_current_nodes();

	if ( empty( $nodes ) ) {
		return;
	}

	if ( 1 === count( $nodes ) ) {
		$payload = $nodes[0];

		if ( ! isset( $payload['@context'] ) ) {
			$payload = array( '@context' => 'https://schema.org' ) + $payload;
		}
	} else {
		// Multiple entities: hoist the context and wrap in an @graph, so the
		// result stays a valid JSON-LD document rather than a bare array.
		$graph = array();

		foreach ( $nodes as $node ) {
			unset( $node['@context'] );
			$graph[] = $node;
		}

		$payload = array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		);
	}

	$json = wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG );

	if ( false === $json ) {
		return;
	}

	echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'cb_schema_render', 20 );
