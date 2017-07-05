<!DOCTYPE html>

<html>
  <head>
	<script   src="https://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>

    <title>Create a new checklist</title>
		<script>
			var numFields = 1;
			function addLine(calledBy) {
				console.log( calledBy);
				if(typeof(calledBy) == "undefined" || calledBy.id == "detail-"+(numFields-1)) {
					console.log(calledBy);
					$("#inputFields").append("<b>"+numFields+"</b> "
					+ "Depends On: <input type='number' name='data["+numFields+"][depend]' id='depend-"+numFields+"'>"
					+ "Detail: <input type='text' name='data["+numFields+"][detail]' id='detail-"+numFields+"' onFocus='addLine(this)'></b><br>");
					numFields++;
				}
			}
			function fillLines(data) {
				console.log(data);
				console.log(data.length);
				for(var i = 0; i < data.length; i++) {
					var inputNumber = i+1;
					addLine();
					console.log(data[i]["data"]);
					$('#detail-'+(inputNumber)).val(data[i]["data"]);
					$('#depend-'+(inputNumber)).val(data[i]["depend"]);
				}
			}
			$(document).ready(function() {
				addLine();
				queryPos = window.location.href.indexOf('?');
				if (queryPos > -1) {
					var checklistNumber = window.location.href.slice(queryPos + 1);
					$.getJSON( "./get.php?"+checklistNumber, function(result) {
						data = result['item'];
						$("#description").val(result['master']['description']);
				}).done(function() {fillLines(data)});
				}
				$('#depend-1').focus();
			})
			
</script>
  </head>
  <body>
		<form action="post.php" method="post">
			<?php if(!empty($_GET['editPass'])):?>
			<input type="hidden" name="parent" value="<?=$_GET['parent']?>">
			<input type="hidden" name="editPass" value="<?=$_GET['editPass']?>">
			<?php endif;?>
			<b>Give your checklist a name:</b> <input type="text" id="description" name="description"><br>
			<span id="inputFields"></span>
			<button type="submit">Submit</button>
		</form>
		<b>Warning, this project is in an unfinished state. The database may be erased at any time.</b>
  </body>
</html>
