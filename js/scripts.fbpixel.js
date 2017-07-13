jQuery( "#fbpixelForm" ).submit(function( event ) {
// Stop form from submitting normally
	jQuery(".darkenBG").show(); // Show Loading Box and Darken Background
	var url = jQuery( "#fbpixelForm" ).attr ("action");
	jQuery.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        data: jQuery('form#fbpixelForm').serialize(),
        success: function(data) {
		jQuery( "#res" ).html( data );
                 }
    });

	
	event.preventDefault(); 
});

jQuery( "#installEvent" ).submit(function( event ) {
	// Stop form from submitting normally
		jQuery(".darkenBG").show(); // Show Loading Box and Darken Background
		var url = jQuery( "#installEvent" ).attr ("action");
		jQuery.ajax({
	        url: url,
	        type: 'post',
	        dataType: 'html',
	        data: jQuery('form#installEvent').serialize(),
	        success: function(data) {
			jQuery( "#res" ).html( data );
	                 }
	    });

		
		event.preventDefault(); 
	});

function cloneRow() {
    var row = document.getElementById("rowToClone"); // find row to copy
    var table = document.getElementById("tableToModify"); // find table to append to
    var clone = row.cloneNode(true); // copy children too
    clone.id = "rowToClone"; // change id or other attributes/contents
    table.appendChild(clone); // add new row to end of table
  }

  function removeRow() {
      var row = document.getElementById("rowToClone"); // find row to remove
      var table = document.getElementById("tableToModify"); // find table to remove the row from it
      table.removeChild(table.childNodes[0]); // delete that row by ID
    }

  function createRow() {
    var row = document.createElement('tr'); // create row node
    var col = document.createElement('td'); // create column node
    var col2 = document.createElement('td'); // create second column node
    row.appendChild(col); // append first column to row
    row.appendChild(col2); // append second column to row
    col.innerHTML = "qwe"; // put data in first column
    col2.innerHTML = "rty"; // put data in second column
    var table = document.getElementById("tableToModify"); // find table to append to
    table.appendChild(row); // append row to table
  }