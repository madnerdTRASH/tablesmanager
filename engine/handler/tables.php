<?php
/* TABLE AJAX HANDLER 

Manage AJAX request from table_handler.js
(FIELDS) ==> You have to modify this to handle new fields!

ADD ROW
-------
Generate a new Row

DEL ROW
-------
Delete a row

SAVE MODIFIED ROW (FIELDS)
------------
Copy row modification inside the database

TODO:
ADD NEW ROW 
--> AddColumn should be AddRow ?
--> Documnentation Helper to add
*/

//Dirty Fix to Include Directory issue
$include_path =  str_replace("engine/handler/tables.php", "",  $_SERVER['SCRIPT_FILENAME']);
set_include_path($include_path);

require_once("common.php");


	/* ADD NEW ROW */
	if (isset($_POST["add_table"]))
	{
	//Get Variable Table and ID
		$table = $_POST["add_table"];
		$addid_counter = $_POST["add_id"];

	//Get Fields Properties
		$struct = Generate::Table_getfields($table,"../../".DB_NAME);
		foreach ($struct as $key => $value) {
			$fields[$key] = $value["name"];
			$type[$key] = $value["type"];
			$link[$key] = $value["key"];

		}

	//Generate Row
		echo Generate::AddColumn($table,$fields,$addid_counter);
	}

/*

/* DEL ROW */

if (isset($_POST["del_table"]))
{
		//Convert Brute Query Informations to Query Informations Readable for Medoo

	$del_table = $_POST["del_table"];
	$del_id = $_POST['del_id'];
	//Instanciate Database;

	//Update Value to the Database
	$database = new medoo("../../".DB_NAME);
	$database->delete($del_table,array("id" => $del_id));
	
}


/*

SAVE ALL MODIFIED ROWS

*/

if (isset($_POST["save_table"]))
{

	//Get number of modification to do
	$n_rows =  count($_POST["save_id"]);

	//Get Table
	$table = $_POST["save_table"];
	
	//Get ID modified / Added
	$id = $_POST["save_id"];

	//Is the cell contains raw data (image) ?
	$isimage = $_POST["isimage"];

	//Upload Images dir
	$uploaddir = 'img';

	//Get Cells value
	$cells = $_POST["save_cell"];

	$struct = Generate::Table_getfields($table,"../../".DB_NAME);
	foreach ($struct as $key => $value) {
		$fields[$key] = $value["name"];
		$type[$key] = $value["type"];
		$link[$key] = $value["key"];
	}

	//If the cell contains data convert it to an image file 


	foreach ($isimage as $key_row => $isimage_row) {

		foreach ($isimage_row as $key_cell => $isimage_cell) {
			
			if ($isimage_cell != "0")
			{
				// Separate out the data	
				$data = explode(',', $cells[$key_row][$key_cell]);

				// Encode it correctly
				$encodedData = str_replace(' ','+',$data[1]);
				$decodedData = base64_decode($encodedData);

				// Get the mime
				$getMime = explode('.', $isimage_cell);
				$mime = end($getMime);

				// We will create a random name!
				$randomName = substr_replace(sha1(microtime(true)), '', 12).'.'.$mime;


				if(file_put_contents("../../".$uploaddir."/".$randomName, $decodedData)) {
					$cells[$key_row][$key_cell] = $uploaddir."/".$randomName;

				}
				else {
				// Show an error message should something go wrong.
					echo "Something went wrong. Check that the file isn't corrupted";
				}



			}

		}
		
	}
	$database = new medoo("../../".DB_NAME);
	//Handle new rows
	$last_id = $database->select($table,"id","ORDER BY id DESC LIMIT 1");
	

	for ($i=0; $i < $n_rows; $i++) 
	{ 
		$assoc = array_combine($fields , $cells[$i]); //Combine Fields and Value

		//Verify if the row exist
		if ($id[$i] <= $last_id[0])
		{
			$database->update($table,$assoc,array("id" => $id[$i])); //If the row exist modify it

		}
		else
		{
			$database->insert($table,$assoc); //If the row doesn't exist create it
		}

	}
	

}


?>