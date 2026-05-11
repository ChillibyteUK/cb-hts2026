<?php
/**
 * Person contact form integration with Gravity Forms.
 *
 * The cb-people block and the Service Sidebar page template render a
 * "Contact {firstname}" link that opens a shared Bootstrap modal containing
 * a Gravity Forms form (ID stored in the `contact_person_form_id` ACF option).
 *
 * The form carries a hidden "Recipient PID" field (inputName = recipient_pid)
 * whose value is set to the person's post ID by JS when the modal opens.
 *
 * On submission this module:
 *  1. Validates the post ID points to a published `person` post.
 *  2. Routes the notification To: to the site-wide contact_email option.
 *  3. CCs the person's own email_address ACF field if one is set.
 *  4. Sets Reply-To to the submitter's email.
 *  5. Prepends "FAO {name}:" to the notification subject.
 *
 * Email addresses are NEVER rendered into page markup.
 *
 * Field IDs are resolved by GF admin label (case-insensitive) with type-based
 * fallbacks, cached per form version to survive GF field renumbering.
 *
 * @package cb-hts2026
 */

defined( 'ABSPATH' ) || exit;

/**
 * Return the configured contact form ID from site-wide ACF options.
 *
 * @return int 0 if not configured.
 */
function cb_people_get_contact_form_id() {
	if ( ! function_exists( 'get_field' ) ) {
		return 0;
	}
	return (int) get_field( 'contact_person_form_id', 'option' );
}

/**
 * Resolve known field IDs on the contact form by admin label, with type-based
 * fallbacks. Cached per form version string.
 *
 * Resolved keys: version, name, email, phone, message, recipient.
 *
 * @param int $form_id Gravity Forms form ID.
 * @return array{version:string,name:?int,email:?int,phone:?int,message:?int,recipient:?int}|null
 */
function cb_people_resolve_form_fields( $form_id ) {
	$form_id = (int) $form_id;
	if ( ! $form_id || ! class_exists( 'GFAPI' ) ) {
		return null;
	}

	$cache_key = 'cb_people_form_fields_' . $form_id;
	$form      = GFAPI::get_form( $form_id );
	if ( ! $form ) {
		return null;
	}
	$version = isset( $form['version'] ) ? (string) $form['version'] : '';

	// Build valid field ID set to detect stale cache after field deletions.
	$valid_ids = array();
	if ( ! empty( $form['fields'] ) && is_array( $form['fields'] ) ) {
		foreach ( $form['fields'] as $f ) {
			$fid = is_object( $f ) ? (int) $f->id : (int) ( $f['id'] ?? 0 );
			if ( $fid ) {
				$valid_ids[ $fid ] = true;
			}
		}
	}

	$cached = get_transient( $cache_key );
	if ( is_array( $cached ) && isset( $cached['version'] ) && $cached['version'] === $version ) {
		$stale = false;
		foreach ( array( 'name', 'email', 'phone', 'message', 'recipient' ) as $k ) {
			if ( ! empty( $cached[ $k ] ) && empty( $valid_ids[ (int) $cached[ $k ] ] ) ) {
				$stale = true;
				break;
			}
		}
		if ( ! $stale ) {
			return $cached;
		}
	}

	$by_label      = array();
	$by_input_name = array();
	$by_type       = array();

	if ( ! empty( $form['fields'] ) && is_array( $form['fields'] ) ) {
		foreach ( $form['fields'] as $f ) {
			$admin_label = '';
			$label       = '';
			$input_name  = '';
			if ( is_object( $f ) ) {
				$admin_label = (string) ( $f->adminLabel ?? '' ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$label       = (string) ( $f->label ?? '' );
				$input_name  = (string) ( $f->inputName ?? '' ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			} elseif ( is_array( $f ) ) {
				$admin_label = (string) ( $f['adminLabel'] ?? '' );
				$label       = (string) ( $f['label'] ?? '' );
				$input_name  = (string) ( $f['inputName'] ?? '' );
			}
			$type = is_object( $f ) ? (string) $f->type : (string) ( $f['type'] ?? '' );
			$id   = is_object( $f ) ? (int) $f->id : (int) ( $f['id'] ?? 0 );
			if ( ! $id ) {
				continue;
			}
			$key = strtolower( trim( '' !== $admin_label ? $admin_label : $label ) );
			if ( '' !== $key && ! isset( $by_label[ $key ] ) ) {
				$by_label[ $key ] = $id;
			}
			if ( '' !== $input_name && ! isset( $by_input_name[ $input_name ] ) ) {
				$by_input_name[ $input_name ] = $id;
			}
			$by_type[ $type ][] = $id;
		}
	}

	$first_of = function ( $type ) use ( $by_type ) {
		return isset( $by_type[ $type ][0] ) ? (int) $by_type[ $type ][0] : null;
	};

	$resolved = array(
		'version'   => $version,
		'name'      => $by_label['name'] ?? ( $first_of( 'name' ) ? $first_of( 'name' ) : $first_of( 'text' ) ),
		'email'     => $by_label['email'] ?? $first_of( 'email' ),
		'phone'     => $by_label['phone'] ?? $first_of( 'phone' ),
		'message'   => $by_label['message'] ?? $first_of( 'textarea' ),
		'recipient' => $by_input_name['recipient_pid']
			?? $by_label['recipient person id']
			?? $by_label['recipient pid']
			?? $first_of( 'hidden' ),
	);

	set_transient( $cache_key, $resolved, DAY_IN_SECONDS );
	return $resolved;
}

/**
 * Module-scoped store for recipient data resolved during pre-submission,
 * read back during notification routing.
 *
 * @param array|null $set Pass an array to set; null to read.
 * @return array{pid:int,email:string,name:string}|null
 */
function cb_people_recipient_store( $set = null ) {
	static $store = null;
	if ( is_array( $set ) ) {
		$store = $set;
	}
	return $store;
}

/**
 * Returns false the first time it is called, true on every subsequent call.
 * Used to ensure the contact modal HTML (and its inline assets) are only
 * emitted once per page, whether the trigger comes from the cb-people block,
 * the service sidebar template, or both.
 *
 * @return bool
 */
function cb_people_modal_emitted() {
	static $emitted = false;
	if ( $emitted ) {
		return true;
	}
	$emitted = true;
	return false;
}

/**
 * Register GF filters scoped to the configured form ID.
 */
function cb_people_register_gf_hooks() {
	$form_id = cb_people_get_contact_form_id();
	if ( ! $form_id ) {
		return;
	}
	add_filter( "gform_pre_submission_filter_{$form_id}", 'cb_people_validate_recipient' );
	add_filter( "gform_notification_{$form_id}", 'cb_people_route_notification', 10, 2 );
}
add_action( 'init', 'cb_people_register_gf_hooks' );

/**
 * Validate the submitted recipient_pid. If valid, stash recipient details for
 * the notification stage. Always returns the form unchanged.
 *
 * @param array $form GF form definition.
 * @return array
 */
function cb_people_validate_recipient( $form ) {
	$ids = cb_people_resolve_form_fields( (int) $form['id'] );
	if ( ! $ids || empty( $ids['recipient'] ) ) {
		return $form;
	}

	$pid = isset( $_POST[ 'input_' . $ids['recipient'] ] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
		? (int) $_POST[ 'input_' . $ids['recipient'] ]     // phpcs:ignore WordPress.Security.NonceVerification.Missing
		: 0;

	if ( $pid <= 0 ) {
		return $form;
	}

	$post = get_post( $pid );
	if ( ! $post || 'person' !== $post->post_type || 'publish' !== $post->post_status ) {
		return $form;
	}

	$person_email = (string) get_field( 'email_address', $pid );

	cb_people_recipient_store(
		array(
			'pid'   => $pid,
			'email' => $person_email && is_email( $person_email ) ? $person_email : '',
			'name'  => get_the_title( $pid ),
		)
	);

	return $form;
}

/**
 * Route the GF notification at send time.
 *
 * To:       site-wide contact_email option (always).
 * CC:       person's email_address ACF field (if set).
 * Reply-To: submitter's email via GF merge tag.
 * Subject:  prepended with "FAO {name}:".
 *
 * @param array $notification GF notification.
 * @param array $form         GF form.
 * @return array
 */
function cb_people_route_notification( $notification, $form ) {
	$site_email = function_exists( 'get_field' ) ? (string) get_field( 'contact_email', 'option' ) : '';
	$store      = cb_people_recipient_store();

	if ( ! $store ) {
		// No valid recipient resolved — route to site contact only, flag subject.
		if ( $site_email ) {
			$notification['toType'] = 'email';
			$notification['to']     = $site_email;
			$notification['cc']     = '';
		}
		if ( ! empty( $notification['subject'] ) ) {
			$notification['subject'] = '[unrouted] ' . $notification['subject'];
		}
		return $notification;
	}

	// Route To: site contact.
	if ( $site_email ) {
		$notification['toType'] = 'email';
		$notification['to']     = $site_email;
	}

	// CC: the person's own email if available.
	$notification['cc'] = $store['email'] ? $store['email'] : '';

	// Reply-To: submitter's email field.
	$ids = cb_people_resolve_form_fields( (int) $form['id'] );
	if ( $ids && ! empty( $ids['email'] ) ) {
		$notification['replyTo'] = '{:' . $ids['email'] . '}';
	}

	// Prepend FAO to subject.
	if ( ! empty( $notification['subject'] ) ) {
		$notification['subject'] = 'FAO ' . $store['name'] . ': ' . $notification['subject'];
	}

	return $notification;
}
