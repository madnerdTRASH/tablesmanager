<?php

/* 
FOOTER 

TODO:
Description
Comments
TABLES should be in UPPERCASE since it is a global constant
Put Handler inside another files

*/

/* TABLES HANDLER and  Image Dnd Handler */

if (defined('tables'))
{
	$tpl->assign('tables','true');

	$tpl->assign('tables_link','src="engine/handler/tables.js"');
	$tpl->assign('image_handler','src="engine/handler/imageDnD.js"');
}
else
{
	$tpl->assign('tables','false');
}



/* Jquery handler */
$jquery = 'src="engine/inc/jquery.js"';
$tpl->assign('jquery',$jquery);

/* Alert Handler */
$alert_handler = 'src="engine/handler/alert.js"';
$tpl->assign('alert_handler',$alert_handler);



/* LANG HANDLER */
$lang_handler = 'src="engine/handler/lang.js"';
$tpl->assign('lang_handler',$lang_handler);


//If an language is defined than open the translation files, don't open it if the language is default (english)
if (defined('LANG'))
{
	$tpl->assign('lang',LANG);
	$lang_file = 'src="engine/locale/'.LANG.'.js"';
	$tpl->assign('lang_file',$lang_file);
}
else
{
	$tpl->assign('lang','default');
}

$html = $tpl->draw($view);

?>