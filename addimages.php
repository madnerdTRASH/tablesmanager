<?php
/*
 @name: index
 @author: Sarrailh Rémi
 @description: Add Images test
*/

/*Template*/
require_once('header.php'); 
$tpl->assign('scrollmenu',Generate::scrollMenu());
/*Template*/

/*Code Generation*/
$tpl->assign('images',Generate::Table("images")); 
$tpl->assign('galerie',Generate::Table("galerie")); 


/*Template*/
$view = 'addimages';//Html File name (template/addimages.html)
require_once('footer.php');
/*Template*/


?>