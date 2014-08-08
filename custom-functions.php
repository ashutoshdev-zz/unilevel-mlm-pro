<?php
if (!function_exists('getuseridbykey')) {
function getuseridbykey($key)
{
	$table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$id = $wpdb->get_var("
							SELECT id 
							FROM {$table_prefix}mlm_users 
							WHERE `user_key` = '".$key."'
				");
	return $id;
}
}

if (!function_exists('getusernamebykey')) {
function getusernamebykey($key)
{
	$table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$id = $wpdb->get_var("
							SELECT username 
							FROM {$table_prefix}mlm_users 
							WHERE `user_key` = '".$key."'
				");
	return $id;
}
}

if (!function_exists('getuserkeybyid')) {
function getuserkeybyid($user_id)
{
    $table_prefix = mlm_core_get_table_prefix();
	global $wpdb;
	$key = $wpdb->get_var("
							SELECT user_key 
							FROM {$table_prefix}mlm_users 
							WHERE `user_id` = '".$user_id."'
				");
	return $key;
}
}


//return the logeed user's fa_user key
if (!function_exists('get_current_user_key')) {
function get_current_user_key()
{
	$table_prefix = mlm_core_get_table_prefix();
	
	global $current_user, $wpdb;
	
	get_currentuserinfo();

	$username = $current_user->user_login;

	$user_key = $wpdb->get_var("
																												SELECT user_key 
																												FROM {$table_prefix}mlm_users 
																												WHERE username = '".$username."'
																								");
	return $user_key;
}


}

if (!function_exists('PayNowOptions')) {
function PayNowOptions($user_id,$page)
{
	global $current_user, $wpdb;
	$table_prefix = mlm_core_get_table_prefix();

	$user_info = get_userdata($user_id);
    
	//Admin Parameters
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	$currency=$mlm_general_settings['currency'];
	$mlm_pay_settings = get_option('wp_mlm_payment_settings'); 
	$mlm_method = get_option('wp_mlm_payment_method');

    $firstname= get_user_meta($user_id, 'first_name',true);
	$lastname=get_user_meta($user_id, 'last_name',true);
	$city=get_user_meta($user_id, 'user_city',true);
    $email=$user_info->user_email;
	$state=get_user_meta($user_id, 'user_state',true);

	$postalcode=get_user_meta($user_id, 'user_postalcode',true);
	$telephone=get_user_meta($user_id, 'user_telephone',true);
	$address1=get_user_meta($user_id, 'user_address1',true);
	$address2=get_user_meta($user_id, 'user_address2',true);

	 $check_paid = $wpdb->get_var("SELECT payment_status FROM {$table_prefix}mlm_users WHERE user_id = '".$user_id."'");


if($page=='join_net' || $page=='register_user')
{
$OrderID=guid();
$u_id= $wpdb->get_var("SELECT id FROM {$table_prefix}mlm_users WHERE user_id = '".$user_id."'");
 $insert = "INSERT INTO {$table_prefix}mlm_payment_status   set order_id = '{$OrderID}', user_id = '{$u_id}'"; 
$wpdb->query($insert);
}
else if($page=='dashboard')
{
 $member_id = $wpdb->get_var("SELECT id FROM {$table_prefix}mlm_users  WHERE `user_id` = '".$user_id."'");
 $OrderID=$wpdb->get_var("SELECT order_id FROM {$table_prefix}mlm_payment_status  WHERE `user_id` = '".$member_id."'");
}

$member_page_id = $wpdb->get_var("SELECT id FROM {$table_prefix}posts  WHERE `post_content` LIKE '%mlm-payment-process%'	AND `post_type` != 'revision'");

$Amount=$wpdb->get_var("SELECT product_price FROM {$table_prefix}mlm_users WHERE user_id = '".$user_id."'");
$TransactionDateTime=gatewaydatetime();

$CustomerName = stripGWInvalidChars($firstname)." ".stripGWInvalidChars($lastname);
$Address1 =  stripGWInvalidChars($address1);
$Address2 =  stripGWInvalidChars($address2);
$Address3 =  "";
$Address4 =  "";
$City =  stripGWInvalidChars($city);
$State =  stripGWInvalidChars($state);
$PostCode =  stripGWInvalidChars($postalcode);
$EmailAddress =  stripGWInvalidChars($email);
$PhoneNumber =  stripGWInvalidChars($telephone);


if($mlm_method['mlm_payment_method']=='cardsave' && !isset($spnsr_set)) {

$CountryCode=826;
$CurrencyCode=826;

$CallbackURL=$mlm_pay_settings['mlm-site-address']."/?page_id=".$member_page_id;
$pfc=$mlm_pay_settings["mlm-pre-shared-key"];
$MerchantID=$mlm_pay_settings["mlm-merchant-id"];
$merchantpassword=$mlm_pay_settings["mlm-merchant-password"];

$OrderDescription=!empty($mlm_pay_settings["item_name"])? $mlm_pay_settings["item_name"] :'Unilevel Pro';

//Generate Hashstring - use combination of post variables and variables stripped of invalid characters
$HashString="PreSharedKey=" . $pfc;
$HashString=$HashString . '&MerchantID=' . $MerchantID;
$HashString=$HashString . '&Password=' . $merchantpassword;
$HashString=$HashString . '&Amount=' . $Amount;
$HashString=$HashString . '&CurrencyCode=' . $CurrencyCode;
$HashString=$HashString . '&EchoAVSCheckResult=' . 'true';
$HashString=$HashString . '&EchoCV2CheckResult=' . 'true';
$HashString=$HashString . '&EchoThreeDSecureAuthenticationCheckResult=' . 'true';
$HashString=$HashString . '&EchoCardType=' . 'true';
$HashString=$HashString . '&OrderID=' . $OrderID;
$HashString=$HashString . '&TransactionType=' . 'SALE';
$HashString=$HashString . '&TransactionDateTime=' . $TransactionDateTime;
$HashString=$HashString . '&CallbackURL=' . $CallbackURL;
$HashString=$HashString . '&OrderDescription=' . $OrderDescription;
$HashString=$HashString . '&CustomerName=' . $CustomerName;
$HashString=$HashString . '&Address1=' . $Address1;
$HashString=$HashString . '&Address2=' . $Address2;
$HashString=$HashString . '&Address3=' . $Address3;
$HashString=$HashString . '&Address4=' . $Address4;
$HashString=$HashString . '&City=' . $City;
$HashString=$HashString . '&State=' . $State;
$HashString=$HashString . '&PostCode=' . $PostCode;
$HashString=$HashString . '&CountryCode=' . $CountryCode;
$HashString=$HashString . '&EmailAddress=' . $EmailAddress;
$HashString=$HashString . '&PhoneNumber=' . $PhoneNumber;
$HashString=$HashString . '&EmailAddressEditable=' . 'false';
$HashString=$HashString . '&PhoneNumberEditable=' . 'false';
$HashString=$HashString . "&CV2Mandatory=" . 'true';
$HashString=$HashString . "&Address1Mandatory=" . 'true';
$HashString=$HashString . "&CityMandatory=" . 'true';
$HashString=$HashString . "&PostCodeMandatory=" . 'true';
$HashString=$HashString . "&StateMandatory=" . 'true';
$HashString=$HashString . "&CountryMandatory=" . 'true';
$HashString=$HashString . "&ResultDeliveryMethod=" . 'POST';
$HashString=$HashString . "&ServerResultURL=" . '';
$HashString=$HashString . "&PaymentFormDisplaysResult=" . 'false';

//Encode HashDigest using SHA1 encryption (and create HashDigest for later use) - This is used as a checksum by the gateway to ensure form post hasn't been tampered with.
 $HashDigest = sha1($HashString); 


?>
<form name="contactForm" id="contactForm" method="post" action="https://mms.cardsaveonlinepayments.com/Pages/PublicPages/PaymentForm.aspx" target="_self">
        <input type="hidden" name="HashDigest" value="<?php echo $HashDigest; ?>">
		<input type="hidden" name="MerchantID" value="<?php echo $MerchantID; ?>">
		<input type="hidden" name="Amount" value="<?php echo $Amount; ?>">                                       
		<input type="hidden" name="CurrencyCode" value="<?php echo $CurrencyCode; ?>">
		<input type="hidden" name="EchoAVSCheckResult" value="true">
		<input type="hidden" name="EchoCV2CheckResult" value="true">
		<input type="hidden" name="EchoThreeDSecureAuthenticationCheckResult" value="true">
		<input type="hidden" name="EchoCardType" value="true">
		<input type="hidden" name="OrderID" value="<?php echo $OrderID;?>">
		<input type="hidden" name="TransactionType" value="SALE">
		<input type="hidden" name="TransactionDateTime" value="<?php echo $TransactionDateTime; ?>">
		<input type="hidden" name="CallbackURL" value="<?php echo $CallbackURL; ?>">
		<input type="hidden" name="OrderDescription" value="<?php echo $OrderDescription; ?>">
		<input type="hidden" name="CustomerName" value="<?php echo $CustomerName; ?>">
		<input type="hidden" name="Address1" value="<?php echo $Address1; ?>">
		<input type="hidden" name="Address2" value="<?php echo $Address2; ?>">
		<input type="hidden" name="Address3" value="<?php echo $Address3; ?>">
		<input type="hidden" name="Address4" value="<?php echo $Address4; ?>">
		<input type="hidden" name="City" value="<?php echo $City; ?>"> 
		<input type="hidden" name="State" value="<?php echo $State; ?>">
		<input type="hidden" name="PostCode" value="<?php echo $PostCode; ?>">
		<input type="hidden" name="CountryCode" value="<?php echo $CountryCode; ?>">
		<input type="hidden" name="EmailAddress" value="<?php echo $EmailAddress; ?>">
		<input type="hidden" name="PhoneNumber" value="<?php echo $PhoneNumber; ?>">
		<input type="hidden" name="EmailAddressEditable" value="false">
		<input type="hidden" name="PhoneNumberEditable" value="false">
		<input type="hidden" name="CV2Mandatory" value="true">
		<input type="hidden" name="Address1Mandatory" value="true">
		<input type="hidden" name="CityMandatory" value="true">
		<input type="hidden" name="PostCodeMandatory" value="true">
		<input type="hidden" name="StateMandatory" value="true">
		<input type="hidden" name="CountryMandatory" value="true">
		<input type="hidden" name="ResultDeliveryMethod" value="POST">
		<input type="hidden" name="ServerResultURL" value="">
		<input type="hidden" name="PaymentFormDisplaysResult" value="false">
		<input type="hidden" name="ThreeDSecureCompatMode" value="false">
		<br><input type="submit" value="Pay Now">
	</form>	
	
<?php }
else if($mlm_method['mlm_payment_method']=='paypal' && !isset($spnsr_set) ) {	
$paypal_settings=get_option('wp_mlm_paypal_settings');
$CallbackURL=site_url()."/?page_id=".$member_page_id;
$bussiness=$paypal_settings['paypal_business'];
$account_type=$paypal_settings['paypal_multiple_url'];

$item_name=!empty($paypal_settings['item_name'])?$paypal_settings['item_name']:'UniLevel';


?>

<form action="<?php  echo   $account_type ?>" method="post">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="notify_url" value="<?php  echo   $CallbackURL ?>">
<input type="hidden" name="business" value="<?php  echo   $bussiness ?>">
<input type="hidden" name="item_name" value="<?php  echo   $item_name ?>">
<input type="hidden" name="item_number" value="<?php  echo   $OrderID ?>">
<input type="hidden" name="amount" value="<?php  echo   $Amount ?>">
<input type="hidden" name="quantity" value="1">
<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">

<!--<input type="hidden" name="cancel_return" value="">-->

<input type="submit" value="Pay Now">
</form>
<?php
 } 

}
}

function InsertHierarchy($user_key,$sponsor)
{
	global $wpdb;
	$table_prefix = mlm_core_get_table_prefix();
	
	$mlm_general_settings = get_option('wp_mlm_general_settings');
	$mlm_no_of_level=$mlm_general_settings['mlm-level'];
	
			   $parentUserkey[0]=$user_key;
			   for($i=1;$i<=$mlm_no_of_level;$i++) {
				
				$parentUserkey[$i]=returnMemberParentkey($parentUserkey[$i-1]);
                                if($u=get_option('network_mail', true)==1) {
                                SendMailToAll($parentUserkey[$i],$user_key,$sponsor);
                                }
                                if($parentUserkey[$i]==0 || $parentUserkey[$i]=='')
				{
				
				break;
				}
				else 
				{
				$qry_insert="INSERT INTO {$table_prefix}mlm_hierarchy
						   (pid, cid, level) VALUES
							('".$parentUserkey[$i]."','".$user_key."', '".$i."')";

				$wpdb->query($qry_insert);
				
}
 }
}

add_action('get_footer','saveCookies',25);

function saveCookies()
{
global $wpdb,$table_prefix;

  if(!empty($_GET['sp_name'])) {
	
	$sp_name = $wpdb->get_var("select username from {$table_prefix}mlm_users where username='".$_GET['sp_name']."'");
	if($sp_name)
	{
	?>	<script type='text/javascript'>
			jQuery.cookie('s_name','<?php  echo   $sp_name ?>',{ path: '/' });
		</script>
	<?php 
	}
	}
	else if(!empty($_REQUEST['sp']))
	{
		$sp_name = $wpdb->get_var("select username from {$table_prefix}mlm_users where user_key='".$_REQUEST['sp']."'");
		if($sp_name){
	?>	<script type='text/javascript'>
			jQuery.cookie('s_name','<?php  echo   $sp_name ?>',{ path: '/' });
		</script>
	<?php }
	}
 /*   else
	{
	$sp_name = $wpdb->get_var("select username from {$table_prefix}mlm_users order by id ASC Limit 1");
		if($sp_name){
	?>	<script type='text/javascript'>
			jQuery.cookie('sp_name','<?php  echo   $sp_name ?>',{ path: '/' });
		</script>
	<?php }
	
	
	}*/
//echo $sp_name;
}



function SendMailToAll($users_key,$user_key,$enroller)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();

 $admin_mail=get_option('admin_email');

 $parent_Id = $wpdb->get_var("SELECT user_id FROM {$table_prefix}mlm_users WHERE `user_key` = '".$enroller."'");
 $parent_username=$wpdb->get_var("SELECT user_login FROM {$table_prefix}users WHERE `ID` = '".$parent_Id."'");
 
 $enrol_Id = $wpdb->get_var("SELECT user_id FROM {$table_prefix}mlm_users WHERE `user_key` = '".$users_key."'");
 $enrol_mail=$wpdb->get_var("SELECT user_email FROM {$table_prefix}users WHERE `ID` = '".$enrol_Id."'");

 
 $enroll_info=get_userdata($enrol_Id);
 $enrl_fname=$enroll_info->first_name;

 $user_id= $wpdb->get_var("SELECT user_id FROM {$table_prefix}mlm_users WHERE `user_key` = '".$user_key."'");
 $user_info = get_userdata($user_id);
 $username = $user_info->user_login;
 $email=$user_info->user_email;
 $first_name = $user_info->first_name;
 $last_name = $user_info->last_name;

$siteownwer= get_bloginfo('name');
  

 $to =$enrol_mail;

$headers = "MIME-Version: 1.0"."\r\n";
$headers .= "Content-type:text/html; charset=iso-8859-1" . "\r\n";
$headers .= "From: ".get_bloginfo('name')."<".$admin_mail.">"."\r\n";

$subject=get_option('networkgrowing_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('networkgrowing_email_message', true)));

$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[sponsor]', $parent_username, $message);

$message = str_replace('[sitename]', $siteownwer, $message);


wp_mail( $to, $subject, $message, $headers);

}

// If apply for with drawal From Front End
function WithDrawalProcessMail($Id,$_my_post)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();


$deduction = get_option('wp_mlm_withdrawal_method_settings');
$method=unserialize(stripcslashes($_my_post['withdrawalMode']));
$amount=$_my_post['wamount'];
$mode=$method[0];
$comment=$_my_post['wcomment'];
$admin_mail=get_option('admin_email');

$res = $wpdb->get_var("
							SELECT user_id 
							FROM {$table_prefix}mlm_users 
							WHERE `id` = '".$Id."'
				");
$user_info = get_userdata($res);
$username = $user_info->user_login;
$first_name = $user_info->first_name;
$last_name = $user_info->last_name;
 $name=$first_name." ".$last_name;
if(empty($first_name) && empty($first_name)) {
$name=$username;
} 
  
$to =$admin_mail;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: Admin <'.$admin_mail.'>' . "\r\n";


$subject=get_option('withdrawalintiate_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('withdrawalintiate_email_message', true)));

$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[sponsor]', $parent_username, $message);

$message = str_replace('[sitename]', $siteownwer, $message);

wp_mail( $to, $subject, $message, $headers);

}



function WithDrawalMemberMail($userId,$mode,$amount)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();


$deduction = get_option('wp_mlm_withdrawal_method_settings');

$comment=$_my_post['wcomment'];
$admin_mail=get_option('admin_email');
$res = $wpdb->get_var("
							SELECT user_id 
							FROM {$table_prefix}mlm_users 
							WHERE `id` = '".$userId."'
				");

$user_info = get_userdata($res);
$username = $user_info->user_login;
 $email=$user_info->user_email;
 $first_name = $user_info->first_name;
 $last_name = $user_info->last_name;

$siteownwer= get_bloginfo('name');

$name=$first_name." ".$last_name;
if(empty($first_name) && empty($first_name)) {
$name=$username;
} 
  
$to =$admin_mail;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: Admin <'.$admin_mail.'>' . "\r\n";


$subject=get_option('withdrawalProcess_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('withdrawalProcess_email_message', true)));

$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[amount]', $amount, $message);

$message = str_replace('[withdrawalmode]', $mode, $message);

$message = str_replace('[sitename]', $siteownwer, $message);

wp_mail( $to, $subject, $message, $headers);

}



function PayoutGeneratedMail($userId,$total,$payoutMasterId)
{

global $wpdb;
$table_prefix = mlm_core_get_table_prefix();


$deduction = get_option('wp_mlm_withdrawal_method_settings');
$amount=$total;
$payId=$payoutMasterId;
$comment=$_my_post['wcomment'];
$admin_mail=get_option('admin_email');
$res = $wpdb->get_var("
							SELECT user_id 
							FROM {$table_prefix}mlm_users 
							WHERE `id` = '".$userId."'
				");

$user_info = get_userdata($res);
$username = $user_info->user_login;
$first_name = $user_info->first_name;
$last_name = $user_info->last_name;
 $name=$first_name." ".$last_name;
if(empty($first_name) && empty($first_name)) {
$name=$username;
} 
  
$to =$admin_mail;

$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers .= 'From: Admin <'.$admin_mail.'>' . "\r\n";


$subject=get_option('runpayout_email_subject', true);

$message = nl2br(htmlspecialchars(get_option('runpayout_email_message', true)));
 
$message = str_replace('[firstname]', $first_name, $message);

$message = str_replace('[lastname]', $last_name, $message);

$message = str_replace('[email]', $email, $message);

$message = str_replace('[username]', $username, $message);

$message = str_replace('[amount]', $amount, $message);

$message = str_replace('[payoutid]', $payId, $message);

$message = str_replace('[sitename]', $siteownwer, $message);

wp_mail( $to, $subject, $message, $headers);

}

?>