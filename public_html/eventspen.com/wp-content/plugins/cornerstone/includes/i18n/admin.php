<?php

/**
 * Localize strings for javascript
 */

$accept  = __( 'Yes, proceed', 'cornerstone' );
$decline = __( 'No, take me back', 'cornerstone' );

return array(
	'edit-with-cornerstone'      => __( 'Edit with Cornerstone', 'cornerstone' ),
	'cornerstone-tab'            => __( 'Cornerstone', 'cornerstone' ),
	'insert-cornerstone'         => __( 'Insert Shortcode', 'cornerstone' ),
	'updating'                   => __( 'Updating', 'cornerstone' ),
	'confirm-yep'                => __( 'Yep', 'cornerstone' ),
	'confirm-nope'               => __( 'Nope', 'cornerstone' ),
	'manual-edit-warning'        => __( 'Hold up! You&apos;re welcome to make changes to the content. However, these will not be reflected in Cornerstone. If you edit the page in Cornerstone again, any changes made here will be overwritten. Do you wish to continue?', 'cornerstone' ),
	'overwrite-warning'          => __( 'Hold up! The content has been modified outside of Cornerstone. Editing in Cornerstone will replace the current content. Do you wish to continue?', 'cornerstone' ),
	'manual-edit-accept'         => $accept,
	'manual-edit-decline'        => $decline,
	'overwrite-accept'           => $accept,
	'overwrite-decline'          => $decline,
	'default-title'              => __( 'Cornerstone Draft', 'cornerstone')
);