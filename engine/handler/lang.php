<?php
/*
 @name: functions
 @author: Sarrailh Rémi
 @description: Main functions 
 */

/*LIST OF LANGUAGE SUPPORT */

if (get_lang() == "fr")
{
require_once("engine/locale/fr.php");
require_once("engine/locale/fr_custom.php");
define("LANG","fr");
}

/* ----------------------- */

/*Get Language*/
function get_lang()
{
$lang_browser = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
//Get Language
$lang_browser = explode(",",$lang_browser);
$lang_browser = $lang_browser[0];

//Just take the first parts (for different language)
$lang_browser = explode("-",$lang_browser);
$lang_browser = $lang_browser[0];
return $lang_browser;
}

/*Translate $val if it exist*/
function t($val)
{
	if(isset($GLOBALS['lang'][$val]))
	{
	    return $GLOBALS['lang'][$val];
	}
	else
	{
	    return $val;
	}  
}

?>