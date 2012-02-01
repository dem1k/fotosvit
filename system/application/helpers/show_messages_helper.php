<?php
/**
 *  A helper for displaying error messages.
 */

/**
 *  Builds HTML containing error messages for inserting into views.
 *
 *  @return string
 */
function show_errors() {
	$ci = & get_instance();
	$result = '';
	if ( $errs = $ci->logger->get_error() ) {
		// display general errors
		$result .= '<div class="sys-general"><ul>';
		foreach ( $errs as $err ) {
			$result .= '<li>' . $err . '</li>';
		}
		$result .= '</ul></div>';
	}
	return $result;
}

/**
 *  Builds HTML containing error messages for inserting into views.
 *
 *  @return string
 */
function show_internals() {
	$ci = & get_instance();
	$result = '';
	if ( $errs = $ci->logger->get_internal() ) {
		// display general errors
		$result .= '<div class="sys-internal">Internal Errors: (they won\'t shows users)<ul>';
		foreach ( $errs as $err ) {
			$result .= '<li>' . $err . '</li>';
		}
		$result .= '</ul></div>';
	}
	return $result;
}

/**
 *  Builds HTML with messages for inserting into views.
 *
 *  @return string
 */
function show_messages() {
	$ci = & get_instance();
	$result = '';
	$msgs = $ci->logger->get_messages();
	if ( $msgs ) {
		// if we have any messages
		$result .= '<div class="sys-message">Messages:<ul>';
		foreach ( $msgs as $msg ) {
			$result .= '<li>' . $msg . '</li>';
		}
		$result .= '</ul></div>';
	}
	return $result;
}

/**
 *  Builds HTML with success messages for inserting into views.
 *
 *  @return string
 */
function show_success() {
	$ci = & get_instance();
	$result = '';
	$scss = $ci->logger->get_success();
	if ( $scss ) {
		// if we have any success messages
		$result .= '<div class="sys-success">Success:<ul>';
		foreach ( $scss as $scs ) {
			$result .= '<li>' . $scs . '</li>';
		}
		$result .= '</ul></div>';
	}
	return $result;
}

/**
 *  Builds HTML with validation errors for inserting into views.
 *
 *  @return string
 */
function show_validation_errors() {
	$ci = & get_instance();
	$result = '';
	$scss = $ci->logger->get_validation_errors();
	if ( $scss ) {
		$result .= '<div class="sys-validation-errors">Validation errors:<ul>';
		$result .= $scss;
		$result .= '</ul></div>';
	}
	return $result;
}
?>