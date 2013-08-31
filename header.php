<?php
require_once('common.php');

$name = array(t('Go back'),t('Add Images'));
$link = array('index.php','addimages.php');
$tpl->assign('menu',Generate::Menu($name,$link)); 
?>