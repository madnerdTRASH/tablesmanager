<?php
/*
 @name: functions
 @author: Sarrailh Rémi
 @description: This function generate HTML code

TODO: Automate Database Manipulation
	  Commments!
	  Automate Help
	  CHECKBOX GENERATION : Change Checkbox with bootstrap checkbox (bigger)
	  SEPERATE GENERATOR WITH EACH OBJECTS : TABLE / MENU etc....

*/


	  class Generate{

/*
Get Fields Structure for one Table
*/

public static function Table_getfields($table,$db)
{
	//Instanciate Database;
	$database = new medoo($db);
	$struct_table = $table."_struct";
	$struct_fields = array("name","description","type","key");
	$struct = $database->select($struct_table,$struct_fields);
	return $struct;
}

/*
Get Value of all rows of a field
*/

public static function Table_GetValues($table,$field,$db)
{
	//Instanciate Database;
	$database = new medoo($db);
	$value = $database->select($table,array("id",$field));
	return $value;
}

/* 
Generate a Table
*/ 

public static function Table($table)
{

	$struct = Generate::Table_getfields($table,DB_NAME);

	foreach ($struct as $key => $value) {
		$fields_name[$key] = $value["name"];
		$description[$key]= t($value["description"]);
		$type[$key] = $value["type"];
		$link[$key] = $value["key"];
	}


 	//Generating Headers
	$html = '
	<div class="form-actions">
		<legend>'.ucfirst($table).'</legend>
		<table id="'.$table.'" class="table table-striped table-bordered">
			<tr>';

				foreach ($description as $value)
				{
					$html = $html . '
					<th>' . $value . '</th>';
				}	

				$html = $html . '
				<th>

				</th>			
			</tr>';

    //Generating inside the table
			$database = new medoo(DB_NAME);
			$rows = $database->select($table,$fields_name);
			$id_list = $database->select($table,array("id"));

			if (!empty($id_list)) //We verify that there is data
			{
				//Generating rows
				foreach ($id_list as $key_id => $id_array) {
					$id = $id_array["id"];
					$html = $html . '<tr id="row-'.$id.'">';

					//Generating Cell
					foreach ($fields_name as $key => $field) 
					{
						$html = $html . Generate::cell($type[$key],$field,$rows[$key_id][$field],$link[$key],DB_NAME);						
					}
				//Generating Actions Buttons
					$html = $html . '
					<td>		
						<button class="right delbutton btn btn-danger"><i class="icon-remove icon-white"></i></button>
					</td>
				</tr>'

				;

			}

		}

			//It there is no datas to display
		else
		{	
			//Table is empty
		}



		//Generating Footer
		$html = $html . '
	</table>
	<button class="addbutton btn btn-success '.$table.'"><i class="icon-plus icon-white"></i></button>
	<button class="right save btn btn-danger '.$table.'">'.t("Save").'</button>
</div>

';

		//Add Javascript to handle table in footer
if (!defined('tables'))
{
	define("tables","true");
}

return $html;
}



/*

Generate Cell

$type = Type of fields
$id = Name of field
$value = Value of field
$link = Name of field of an external table
$db = Database Path

*/

public static function cell($type,$id,$value,$link,$db)
{
//Type of field
	switch ($type)
	{
		//Textbox 
		case "text" :
		$html = '					
		<td>
			<input type="text" id="'.$id.'" class="text_field" value="'.$value.'">
		</td>';
		break;

		//External data
		case "choice" :

		//Get External Table from ID
		$id = explode("_", $id);
		$ext_table = $id[1];
		//Get Value of External Table
		$fields = Generate::Table_GetValues($ext_table,$link,$db);

		$html = '				
		<td>
			<select class="choice_field">
				';

		//WE REQUIRE MOAR ID


				foreach ($fields as $key => $field) {	
					
					if ($field["id"] == $value) 
						{
						$selected = 'selected="selected"';
					} else {
						$selected = "";
					}
					$html = $html . '
					<option value="'.$field["id"].'" '.$selected.'>'
						.$field[$link].'
					</option>';
				}

				//If there is no ID than but Not Specified by Default. (Anyway always have an Not Specifed Field)
				if ($value == "") {$html = $html . '
					<option value="" selected="selected">'.t("Not Specified").'</option>';} 
				else {$html = $html .'
					<option value="">'.t("Not Specified").'</option>"';}
				$html = $html . '
			</select>
		</td>
		';
		
		break;



		//Images
		case "image" :

		//If there is no image then put dropicon image
		if ($value == "") {$value = "img/dropicon.png";}	
		
		$html = '	
		<td>
			<span class="thumbnail image_field">
				<img src="'.$value.'">
			</span>
		</td>
		';

		break;

		case "checkbox" :
		if ($value == 1) {$value = "checked";} else {$value = "";}
		$html = '
		<td>
			<input type="checkbox" class="checkbox_field" '.$value.' >
		</td>
		';
		break;

		default :
		$html = '
		<td class="error">
			<span>
				<code>'.t("ERROR - FIELD UNKNOWN (verify database)").'</code>
			</span>
		</td>
		';
		break;


	}
	return $html;
}

/* 


Add A Column AJAX

*/

public static function AddColumn($table,$fields,$addid_counter)
{


//TO DO : Redundant Function with Table! FIX THIS!

 	//Instanciate Database;
	$database = new medoo("../../".DB_NAME);
	

	$struct = Generate::Table_getfields($table,"../../".DB_NAME);

	foreach ($struct as $key => $value) {
		$fields_name[$key] = $value["name"];
		$description[$key]= $value["description"];
		$type[$key] = $value["type"];
		$link[$key] = $value["key"];
	}

  		//Get last ID
	$id = $database->select($table,"id","ORDER BY id DESC LIMIT 1");
	if (!empty($id[0]))
	{
		$id = $id[0] + $addid_counter;
	}
	else
	{
			$id = 0 + $addid_counter; //If table is empty id = 0
		}

//Create Row with ID
		$html = '<tr id="row-'.$id++.'" class="modified error">';

//Create Input with fields
		foreach ($fields_name as $key => $field) 
		{
			$html = $html . Generate::cell($type[$key],$field,"",$link[$key],"../../".DB_NAME);
		}


		$html = $html . '
		<td>
			<button class="right delbutton btn btn-danger"><i class="icon-remove icon-white"></i></button>
		</td>
	</tr>';
	return $html;
}


/* MENU GENERATOR */

public static function Menu($name,$link)
{

	$html  = '<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<ul class="nav">';

				for($i = 0;$i < count($name);$i++) 
				{
					$html = $html . '<li><a href="'.$link[$i].'">'.$name[$i].'</a></li>';
				}


				$html = $html . '
			</ul>
		</div>

	</div>
</div>';
return $html;
}

public static function scrollMenu()
{
	$html = '<div id="scroll_menu" class="navbar-fixed-top">';
	$html = $html . '</div>';

	return $html;
}

}
?>