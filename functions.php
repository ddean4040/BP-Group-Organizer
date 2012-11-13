<?php

if( ! function_exists( 'bp_group_avatar_micro' ) ) {
	
	function bp_group_avatar_micro() {
		echo bp_get_group_avatar_micro();
	}
	
	function bp_get_group_avatar_micro() {
		return bp_get_group_avatar( 'type=thumb&width=15&height=15' );
	}
	
}

function bpgo_is_hierarchy_available() {
	return ( defined( 'BP_GROUP_HIERARCHY_IS_INSTALLED' ) && method_exists( 'BP_Groups_Hierarchy', 'get_tree' ) );
}

?>
