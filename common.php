<?php 
session_start();
/*
 @name: common
 @author: Sarrailh Rémi 
 @description: Configuration files
 */

//Debug
error_reporting(E_ALL);
require_once('engine/generator/generate.class.php');
require_once('engine/handler/lang.php');

require_once('settings.php');
require_once('engine/inc/rain.tpl.class.php');
require_once('engine/inc/medoo.min.php');



//Instanciate Template
$tpl = new RainTPL();

//Define Template Folders
raintpl::configure("base_url", null);
raintpl::configure("tpl_dir","./template/");
raintpl::configure("cache_dir","./engine/tmp/");

$view = '';
 ?>