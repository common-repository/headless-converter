<?php
use HeadlessConverter\Constants;

/**
 * Stop execution if not in Wordpress environment
 */
defined( 'WPINC' ) || die;

header( 'content-type: application/json' );

$data = get_queried_object();
$modified_data = apply_filters( Constants::FILTER_MODIFY_DATA, $data );

print json_encode(
	array(
		'data' => $modified_data,
	)
);
