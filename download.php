<?php

include('constants.php');
include('functions.php');
$download = $_GET['download'];
$sameNetwork = false;
if($_SERVER['REMOTE_ADDR'] == getExternalIP()){
	header( 'Location: ftp://'.getFtpAccount().'@'.getHostnameOverride().'/../..'.$download ) ;
}else{
	die("This file does not exist on this server, go ask some hacker forum/community or google");	
}

?>