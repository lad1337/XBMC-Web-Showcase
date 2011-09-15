<?php

function getMovieDatabsePath(){
	return "XBMC/userdata/Database/MyVideos34.db";
	//XBMC
	//../../Library/Application\ Support/XBMC/
	// /User/lad/Library/
	// MyVideos34.db
}


function getThumbnailPath(){
	$path = "XBMC/userdata/Thumbnails/Video/";
	return $path;

}


function getThumbnailFanartPath(){
	$path = "XBMC/userdata/Thumbnails/Video/Fanart/";
	return $path;

}


function getFtpAccount(){
	$user = "gast";
	$pw = "d1mmer";
	
	return $user.":".$pw;
}


function getHostnameOverride(){
	$name = $_SERVER['SERVER_NAME'];
	// $name = "mywebsite.com"
	return $name;
}


?>