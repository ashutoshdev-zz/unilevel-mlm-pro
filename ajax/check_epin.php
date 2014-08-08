<?php 
require_once( "../../../../wp-config.php" );

global $wpdb, $table_prefix;

	
	if(isset($_GET['q']))
	{
	$q = $_GET['q'];
	$epin = $wpdb->get_var("SELECT epin_no FROM {$table_prefix}mlm_epins WHERE epin_no = '$q' AND status=0");
	if($epin)
	{
		_e("<span class='msg'>Congratulations! This ePin is available.</span>","unilevel-mlm-pro");
	}
	else
	{
		_e("<span class='errormsg'>Sorry! This ePin is not Valid or already Used .</span>","unilevel-mlm-pro");
		
	}
	}
	else if(isset($_GET['r'])) {
		$r = $_GET['r'];
	$epin = $wpdb->get_var("SELECT epin_no FROM {$table_prefix}mlm_epins WHERE epin_no = '$r' AND status=0");
	
		if($epin)
	{
		echo '1';
	}
	else
	{
		echo '0';
		
	}
	
	}
?>
