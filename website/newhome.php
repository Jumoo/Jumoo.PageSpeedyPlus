<?php include 'speedycore.php' ; ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Localgov.PageSpeedy by Jumoo</title>
	<link href='http://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/speedy.css">
  <link rel="stylesheet" href="css/typeahead.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

	<link rel="alternate" type="application/rss+xml" title="RSS" href="newsitesfeed.php" />
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
      <!-- typeahead dropdown thingy -->
      <form method="get" action="speedy.php">
				<div class="form-group">
	        <label for="councils">Pick a Council</label>
					<select class="form-control input-lg" id="councils" name="id">
						<?php

							$db = new SQlite3('speedyplus.db');

							$statement = $db->prepare('SELECT * FROM SITES where active =1 order by Name COLLATE NOCASE;');
							$results = $statement->execute();


							while ($row = $results->fetchArray()) {
							?>
								<option value="<?php echo $row['Id'] ?>"><?php echo $row['Name'] ?></option>
							<?php
							}


							$statement->close();
						?>
					</select>

				</div>
				<div class="text-center">
					<button type="submit" class="btn btn-lg btn-success">View</button>
				</div>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12 col-sm-6 col-sm-offset-3">
      <div class="text-center">
        <ul class="nav nav-pills">
          <li><a href="speedytable.php">Speedys</a></li>
          <li><a href="featurelist.php">App list</a></li>
          <li><a href="achecktable.php">Accessibility</a></li>
          <li><a href="newsites.php">New Sites</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>
 (function( $ ) {
	 $.widget( "custom.combobox", {
		 _create: function() {
			 this.wrapper = $( "<span>" )
				 .addClass( "custom-combobox" )
				 .insertAfter( this.element );

			 this.element.hide();
			 this._createAutocomplete();
			 this._createShowAllButton();
		 },

		 _createAutocomplete: function() {
			 var selected = this.element.children( ":selected" ),
				 value = selected.val() ? selected.text() : "";

			 this.input = $( "<input>" )
				 .appendTo( this.wrapper )
				 .val( value )
				 .attr( "title", "" )
				 .addClass( "form-control input-lg" )
				 .autocomplete({
					 delay: 0,
					 minLength: 0,
					 source: $.proxy( this, "_source" )
				 })
				 .tooltip({
					 tooltipClass: "ui-state-highlight"
				 });

			 this._on( this.input, {
				 autocompleteselect: function( event, ui ) {
					 ui.item.option.selected = true;
					 this._trigger( "select", event, {
						 item: ui.item.option
					 });
				 },

				 autocompletechange: "_removeIfInvalid"
			 });
		 },

		 _createShowAllButton: function() {
			 var input = this.input,
				 wasOpen = false;

			 $( "<a>" )
				 .attr( "tabIndex", -1 )
				 .attr( "title", "Show All Items" )
				 .tooltip()
				 .appendTo( this.wrapper )
				 .button({
					 icons: {
						 primary: "ui-icon-triangle-1-s"
					 },
					 text: false
				 })
				 .removeClass( "ui-corner-all" )
				 .addClass( "custom-combobox-toggle ui-corner-right" )
				 .mousedown(function() {
					 wasOpen = input.autocomplete( "widget" ).is( ":visible" );
				 })
				 .click(function() {
					 input.focus();

					 // Close if already visible
					 if ( wasOpen ) {
						 return;
					 }

					 // Pass empty string as value to search for, displaying all results
					 input.autocomplete( "search", "" );
				 });
		 },

		 _source: function( request, response ) {
			 var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			 response( this.element.children( "option" ).map(function() {
				 var text = $( this ).text();
				 if ( this.value && ( !request.term || matcher.test(text) ) )
					 return {
						 label: text,
						 value: text,
						 option: this
					 };
			 }) );
		 },

		 _removeIfInvalid: function( event, ui ) {

			 // Selected an item, nothing to do
			 if ( ui.item ) {
				 return;
			 }

			 // Search for a match (case-insensitive)
			 var value = this.input.val(),
				 valueLowerCase = value.toLowerCase(),
				 valid = false;
			 this.element.children( "option" ).each(function() {
				 if ( $( this ).text().toLowerCase() === valueLowerCase ) {
					 this.selected = valid = true;
					 return false;
				 }
			 });

			 // Found a match, nothing to do
			 if ( valid ) {
				 return;
			 }

			 // Remove invalid value
			 this.input
				 .val( "" )
				 .attr( "title", value + " didn't match any item" )
				 .tooltip( "open" );
			 this.element.val( "" );
			 this._delay(function() {
				 this.input.tooltip( "close" ).attr( "title", "" );
			 }, 2500 );
			 this.input.autocomplete( "instance" ).term = "";
		 },

		 _destroy: function() {
			 this.wrapper.remove();
			 this.element.show();
		 }
	 });
 })( jQuery );

 $(function() {
	 $( "#councils" ).combobox();
	 $( "#toggle" ).click(function() {
		 $( "#combobox" ).toggle();
	 });
 });
 </script>
</body>
</html>
