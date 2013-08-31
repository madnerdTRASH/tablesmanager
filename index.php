<?php
/*
 @name: index
 @author: Sarrailh Rémi
 @description: Index Page
*/
/*Template*/
require_once('header.php'); 

/*Template*/

/*CUSTOM CODE*/
//Instanciate Database;
$database = new medoo(DB_NAME);

$leds_value = $database->select("leds",array("id","code","name"));
$tpl->assign("leds_value",$leds_value);

/*CUSTOM CODE*/

/*Template*/
$view = 'index';
require_once('footer.php');
/*Template*/

?>