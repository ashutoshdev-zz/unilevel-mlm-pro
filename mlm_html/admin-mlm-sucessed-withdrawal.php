<?php 
function mlm_withdrawal_sucess(){
?>

<div class='wrap'>
	<div id="icon-users" class="icon32"></div><h1><?php _e('Processed Withdrawals Report','unilevel-mlm-pro');?></h1><br />
		<div class="notibar msginfo" style="margin:10px;">
			<a class="close"></a>
			<p><?php _e('Given below is the list of all withdrawal requests that have been successfully processed.','unilevel-mlm-pro');?></p>
		</div>	
</div>

<?php

	require_once('withdrawals-list-table.php');
	$objOrderList = new Withdrawals_List_Table();
	$objOrderList->prepare_items();
	$objOrderList->display();
	$listArr = get_option('sucessed_withdrawal_list');
	
    $value = serialize($listArr);
    ?>
    <form method="post" action="<?php echo plugins_url() ?>/unilevel-mlm-pro/mlm_html/export.php">
        <input type="hidden" name ="listarray" value='<?php echo $value ?>' />
        <input type="hidden" name ="filename" value='sucessed-withdrawal-list-report' />
        <input type="submit" name="export_csv" value="<?php _e('Export to CSV', 'unilevel-mlm-pro'); ?>" class="button-primary"/></form>
    <?php
}
 
?>