
/* 

Image Drag and Drop Handler

*/

function searchNewImage(table,row)
{
	for (var i = 0; i < newImage_table.length; i++) {
		if (newImage_table[i] == table && newImage_row[i] == row)
		{
			return i;
		}
	};
}

function handleFileSelect(evt) {

	//Get table
	table = $(this).parent().parent().parent().parent().attr("id");

	//Get Row
	row = this.parentNode.parentNode.id;

 	//Prevent Default Action (Show images instead of the webpage)
 	evt.stopPropagation();
 	evt.preventDefault();

    var files = evt.dataTransfer.files; // FileList object.

    // files is a FileList of File objects. List some properties.
    var output = [];
    for (var i = 0, f; f = files[i]; i++) {

    	  // Only process image files.
    	  if (!f.type.match('image.*')) {
    	  	notify(t("This is NOT an image"),"error");
    	  	continue;
    	  }

    	  var reader = new FileReader();

     	 // Closure to capture the file information.
     	 reader.onload = (function(theFile) {
     	 	return function(e) {
        // Render thumbnail.
        image_data = resizeImage(reader.result, theFile,table,row)


        $("#" + table + " #" + row + " .image_field").addClass("new_image");


       //Generate Alert Message
       notify(t("Image ")+ theFile.name +t(" Added"),"ok");

       newImage_table.push(table);
       newImage_row.push(row);
       
       newImage_name.push(theFile.name);

 	   //Change Class of row in table
 	   $("#"+ table + " #" + row).addClass("modified error");

	   //Save flag
	   saved = false;
	};
})(f);
 // Read in the image file as a data URL.
 reader.readAsDataURL(f);


 
}

}

function handleDragOver(evt) {
	evt.stopPropagation();
	evt.preventDefault();
    evt.dataTransfer.dropEffect = 'copy'; // Explicitly show this is a copy.

}


function resetDnD()
{

  // Setup the dnd listeners.
  $(".image_field").each(function(index, element) {
    // element is a node with the desired class name
    element.addEventListener('dragover', handleDragOver, false);
    element.addEventListener('drop', handleFileSelect, false);
});
}







/*

Image Drag and Drop 

*/

resetDnD();

resizeImage = function(data, file,table,row) {
	var fileType = file.type,
	maxWidth = 128,
	maxHeight = 128;

// On charge le fichier dans une balise <img>
var image = new Image();
image.src = data;

// Une fois l'image chargée, on effectue les opérations suivantes
image.onload = function() {
// La fonction imageSize permet de calculer la taille finale du fichier en conservant les proportions
var size = imageSize(image.width, image.height, maxWidth, maxHeight),
imageWidth = size.width,
imageHeight = size.height,

// On créé un élément canvas
canvas = document.createElement('canvas');
canvas.width = imageWidth;
canvas.height = imageHeight;

var ctx = canvas.getContext("2d");

// drawImage va permettre le redimensionnement de l'image
// this représente ici notre image
ctx.drawImage(this, 0, 0, imageWidth, imageHeight);

// Permet d'exporter le contenu de l'élément canvas (notre image redimensionnée) au format base64
data_resize = canvas.toDataURL(fileType);

$("#" + table + " #" + row + " .image_field img").attr("src",data_resize);
newImage_content.push(data_resize);

// On supprime tous les éléments utilisés pour le redimensionnement
delete image;
delete canvas;

}

};


// Fonction permettant de redimensionner une image en conservant les proportions
imageSize = function(width, height, maxWidth, maxHeight) {
	var newWidth = width,
	newHeight = height;
	if (width > height) {
		if (width > maxWidth) {
			newHeight *= maxWidth / width;
			newWidth = maxWidth;
		}
	} else {
		if (height > maxHeight) {
			newWidth *= maxHeight / height;
			newHeight = maxHeight;
		}
	}

	return { width: newWidth, height: newHeight };
};