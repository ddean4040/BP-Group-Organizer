<?php

/**
 * Functions for import / export
 */

/**
 * Export groups in CSV format
 * @param string $format short name of format OR list of columns
 * Column lists are based on properties of the Groups object, not columns in the database
 */
function bp_group_organizer_export_csv( $format ) {

	// If Group Hierarchy is not installed, path is equivalent to slug
	if( $format == 'path' && ! bpgo_is_hierarchy_available() ) {
		$format == 'slug';
	}

	if( ! strpos( $format, ',' ) ) {
		// Short name was specified
		switch( $format ) {
			case 'slug':
				$fields = array(
					'creator_id',
					'name',
					'slug',
					'description',
					'status',
					'enable_forum',
					'date_created',
//					'last_activity',
//					'total_member_count'
				);
				
				if( bpgo_is_hierarchy_available() ) {
					$fields[] = 'parent_id';
				}
				
				break;
			case 'path':
				$fields = array(
					'creator_id',
					'name',
					'path',
					'description',
					'status',
					'enable_forum',
					'date_created',
//					'last_activity',
//					'total_member_count'
				);
				break;
			default:
				$fields = apply_filters( 'bp_group_organizer_get_csv_fields_format_' . $format , array() );
				break;
		}
	} else {
		$fields = explode( ',', $format );
	}
	
	if( ! count( $fields ) )	return false;
	
	if( bpgo_is_hierarchy_available() ) {
		$groups_list = array(
			'groups' => BP_Groups_Hierarchy::get_tree()
		);
		$groups_list['total'] = count( $groups_list['groups'] );
	} else {
		$groups_list = BP_Groups_Group::get( 'alphabetical' );
	}
	
	header( 'Content-Type: application/force-download' );
	header( 'Content-Disposition: attachment; filename="' . 'bp-group-export.csv' . '";' );
	
	// Print header row
	echo implode(',', $fields ) . "\n";
	
	foreach( $groups_list['groups'] as $group ) {
		foreach( $fields as $key => $field ) {
			
			if( $field == 'path' ) {
				echo BP_Groups_Hierarchy::get_path( $group->id );
			} else if( in_array( $field, array( 'name', 'description' ) ) ) {
				echo '"' . stripslashes($group->$field) . '"';
			} else {
				echo $group->$field;
			}
			
			if( $key < count($fields) - 1 ) echo ',';
			
		}
		echo "\n";
	}
	
	die();
}

?>