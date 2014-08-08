<?php

if (!function_exists('filter')) {
function filter($data)
{
	$data = trim(htmlentities(strip_tags($data)));
	
	if(get_magic_quotes_gpc())
		$data = stripslashes($data);
	
	$data = mysql_real_escape_string($data);
	
	return $data;
}
}


if (!function_exists('epin_exists')) {
function epin_exists($value)
{
	global $wpdb, $table_prefix;
	$epin = $wpdb->get_var("SELECT epin_no FROM {$table_prefix}mlm_epins WHERE epin_no = '$value' AND status=0");
	if($epin=="")
		return true;
	else
		return false;
}
}

if (!function_exists('checkInputField')) {
function checkInputField($value)
{
	if($value=="")
		return true;
	else
		return false;
}
}

if (!function_exists('confirmPassword')) {
function confirmPassword($pass, $confirm)
{
	if($confirm != $pass)
		return true;
	else
		return false;
}
}

if (!function_exists('confirmEmail')) {
function confirmEmail($email, $confirm)
{
	if($confirm != $email)
		return true;
	else 
		return false;
}
}

if (!function_exists('checkPair')) {
function checkPair($pair1, $pair2)
{
	if($pair1 == "" || $pair2 == "" || $pair1 == 0 || $pair2 == 0)
		return true;
	else
		return false;
}
}

if (!function_exists('checkInitial')) {
function checkInitial($initial)
{
	if($initial == "" || $initial == 0)
		return true;
	else
		return false;
}
}
?>