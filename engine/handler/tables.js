/*
Tables Handler.js
By Rémi Sarrailh Gplv3
Don't undestand something ? contact me at : maditnerd@gmail.com


Description : This function manage the table AJAX/Javascript interaction

(FIELDS) ==> You have to modify this to handle new fields!

Parts:

VARIABLES
--> This part store informations on HTML objects for later use

	TABLE / ROW INFORMATIONS : Get table name / numbers and store numbers of row when the page was loaded

	NEW ROW INFORMATIONS - SCROLL MENU GENERATION
	--> This part store for each table the last row ID
	--> It also generate the Scroll Menu

EVENT MANAGEMENT
--> Button that only modify thing on the client

	SCROLL : Manage the scroll menu button up and down
	ROW CHANGE FLAG: Add class "modified" to an row when a field is modified (FIELDS)

AJAX
--> Button that modify thing on the client AND the server

	DEL ROW : Remove dynamically a row and erase it from the database (AJAX)
	ADD NEW ROW : Generate a new row in php and then append it with jquery (AJAX)
	SAVE ROW : Save all modified row (AJAX) (FIELDS)

GENERIC FUNCTIONS
--> Some Generic Functions that shouldn't be here
Alert Notifications --> Show a fixed notification

IMAGE DRAG AND DROP HANDLER
--> SHOUDN'T BE HERE
Handle image drag and drop

---------------------TODO -------------------------------------
- CLASS SELECTOR : Put fonction for searching last class : search /\s/  
- FIELDS : Handle other fields (Choice)
- SCROLL MENU (hide button)
- SCROLL MENU Fixed scroll tabs not fixed anymore if windows is smaller 
- Hide Scroll menu if it is not necessary at startup
- Table / Row  Selector Functions
- Del New Row shouldn't do an ajax request (This is an BIG performance issue)
---------------------------------------------------------------
*/




/*

VARIABLES

*/


/* TABLE INFORMATIONS */

n_table = $("table").size();

/* NEW ROW INFORMATIONS - SCROLL MENU GENERATION */
var addid_counter = new Array();

//Create an counter for every tables when we add a row the ID doesn't overlaps
//Create a scrollmenu
for (var i = 0; i < n_table; i++) {
	var table_name = ($("table:eq("+i+")").attr("id"));	 //Get name of table on the page
	addid_counter[table_name] = 1; //Add Counter

	//Scroll Menu
	$("#scroll_menu").append("<h3>"+table_name+"</h3><button class='"+table_name+" btn scroll_up'>"+t('UP')+"</button><button class='"+table_name+" btn scroll_down '>"+t('DOWN')+"</button>");
};
$("#scroll_menu").append("<br><br>");

//Initalized saved counter, as nothing is changed by default saved is true
saved = true;

//Data Array with Images
	// Get all of the data URIs and put them in an array
	var newImage_table = new Array();
	var newImage_row = new Array();
	var newImage_content = new Array();
	var newImage_name = new Array();




/* 

EVENT MANAGEMENT

SCROLL : manage the scroll menu
ROW CHANGE FLAG: manage field modification flag


*/


/* SCROLL */

$(".scroll_up").click(function(){

	classes = $(this).attr("class").split(/\s/);
	table = classes[0];
	$(document).scrollTop($("#"+ table).parent().position().top);
});

$(".scroll_down").click(function(){
	classes = $(this).attr("class").split(/\s/);
	table = classes[0];
$(document).scrollTop($("."+ table + ".save").position().top - 128); //TODO Magic Number Really ??!?
});




/*

 ROW CHANGE FLAG 


 */

function modified_flag(object)
{
	//Get table
 	table = $(object).parent().parent().parent().parent().attr("id");

 	//Get Row
 	row = object.parentNode.parentNode.id;

 	//Change Class of row in table
 	$("#"+ table + " #" + row).addClass("modified error")

 	//Save flag
 	saved = false;
}




 /*TEXT FIELD*/

 $(".text_field").keyup(function() {
modified_flag(this);
 });

 /* CHECKBOX FIELD */

 $('input[type=Checkbox]').click(function() {
modified_flag(this);
 });

 /* SELECT FIELD */ 

 $('select').change(function() {
modified_flag(this);

 });

 

 /* IMAGE FIELD (look in imageDnD.js) */




 /* TABLE ACTION BUTTON */


 /* DEL ROW */ 

//Handle Del Button Click
$(document).on( "click", ".delbutton", function(){

	table = $(this).parent().parent().parent().parent().attr("id"); //Retrieve Table Name

	row = $(this).parent().parent().attr("id"); //Retrieve Row ID
	row = row.split("-"); 
	row = row[1]; // Retrieve Database ID

	$.ajax({
		type: "POST",
		url: "engine/handler/tables.php",
		data: { del_table:table, del_id: row}
	}).done(function( html ) {
		notify(t("Row Removed in ")+table,"error")
	});
	$('#'+table+' #row-'+row).remove(); //Remove Row from client view
	

	//Verify if there is still something to save in the table
	n_rows = $("#"+table+" tr").size();

	if (n_rows = $("#"+table+" tr").size() == 1) {saved = true;} //If there is no row there is nothing to save

	for (var i = 1; i < n_rows; i++) 
	{
		unsaved = $("#"+table+" tr:eq("+i+")").hasClass("modified");

		 if (unsaved)
			{
				break; //Something is unsaved so we don't modify the save condition
			}
	}
	if (!unsaved)
	{
		saved = true;
	}
	
});




/* ADD NEW ROW */ 

//Handle Add Button Click
$(".addbutton").click(function() {
//table = this.parentNode.parentNode.parentNode.parentNode.id; 
//Retrieve Table ID
var classes = $(this).attr("class").split(/\s/);
table = classes[classes.length -1];
saved = false; //Saved flag
move_down = false; //It is a dirty fix to maintain the + and save button displayed 

//Send an ajax request to generate an new row
$.ajax({
	type: "POST",
	url: "engine/handler/tables.php",
	data: { add_table: table , add_id: addid_counter[table]}
}).done(function( html ) {


	if (  document.documentElement.clientHeight + 
		$(document).scrollTop() >= document.body.offsetHeight )
	{ 
		move_down = true;
	}

	$("#" + table).append(html); //Append HTML to table for the new row

	//Reset Drag And Drop Handler
	resetDnD();

	addid_counter[table]++; //Add dynamically a row counter
	
	//Focus on last row
	last_row = $("#"+table+" tr").size() - 1;
	$("#"+table+" tr").eq(last_row).children().eq(0).children().focus();


	if (move_down)
	{
		$(document).scrollTop(document.body.offsetHeight);
	}

	//Display an alert
	notify(t("New Row added to ")+table,"ok");
});
});


/* SAVE ROW */

$(".save").click(function(){
//Modified flag set to false until a modified class is found
modified = false;

//Initialize cell / id table
var cell = new Array();
var id = new Array();
var isimage = new Array();


//Get Table name from legend
var classes = $(this).attr("class").split(/\s/);
table = classes[classes.length -1]


//Selectors for all the row of the table selected
row_selector = "#" + table + " tr";
row_total = $(row_selector).size(); //We count the number of rows

mod_row_count = 0; //Modified Rows counter

//LOOP : scan every row
for (var row_count = 0; row_count < row_total; row_count++) {

	table_class = $(row_selector).eq(""+row_count+"").attr("class"); //Get class of row
	

// If a row has been modified (class modified error)
if ( table_class == "modified error")
{

	//Get ID of modified row
	row = $(row_selector).eq(""+row_count+"").attr("id");
	id_array = row.split("-");
	id[mod_row_count] = id_array[1];
	//Get number of cells
	cells_total =  $(row_selector).eq(row_count).children().size();

	//LOOP : scan every cell
	cell[mod_row_count] = new Array();
	isimage[mod_row_count] = new Array();
	for (var cell_count = 0; cell_count < cells_total - 1; cell_count++) {
		
		//Get Cell_type
		cell_type = $(row_selector).eq(row_count).children().eq(cell_count).children().attr("class");
		//If Cell is a text
		if ((cell_type == "text_field") || (cell_type == "choice_field"))
		{
			cell[mod_row_count][cell_count] = $(row_selector).eq(row_count).children().eq(cell_count).children().val();
			isimage[mod_row_count][cell_count] = "0";
		}



		//If cell is a checkbox
		if (cell_type == "checkbox_field")
		{
			checkedornot = $(row_selector).eq(row_count).children().eq(cell_count).children().is(':checked');
			if (checkedornot === true) {cell[mod_row_count][cell_count] = 1;}
			if (checkedornot === false) {cell[mod_row_count][cell_count] = 0;}
			isimage[mod_row_count][cell_count] = "0";
		}

		//If Cell is a image
		if (cell_type == "thumbnail image_field")
		{
			cell[mod_row_count][cell_count] = $(row_selector).eq(row_count).children().eq(cell_count).children().children().attr("src");
			isimage[mod_row_count][cell_count] = "0";
		}

		//If Cell is a new image
		if (cell_type == "thumbnail image_field new_image")
		{
			content_id = searchNewImage(table,row);
			cell[mod_row_count][cell_count] = newImage_content[content_id];
			isimage[mod_row_count][cell_count] = newImage_name[content_id];
		}


	}

	mod_row_count++; // Add one modified row
	modified = true; //Flag table as having modified rows TODO
}

}
//If there is modified row let's save it!
if (modified == true)
{
	$.ajax({
		type: "POST",
		url: "engine/handler/tables.php",
		data: { save_table:table, save_id:id, save_cell: cell, isimage: isimage}
	}).done(function( html ) {

		$('#' +table+' .modified').removeClass("modified error");
		saved = true;
		notify(table + t(" saved"),"ok");
	});

}

else
{
	notify(t("Nothing to save !"),"warning");
}


});



//If there is modified elements on the page don't let the user quit it without confirmation
window.onbeforeunload = function() {

	if (saved==false)
	{
		return t('You have unsaved changes!');
	}
}