function create_vote_area(){//return a string for vote area (div)
	return "<div id = 'vote_area' style='display:none; position:absolute;left:0px;top:0px;z-index:8;height:20%;width:100%;border:1px;background-color:#4d394b;'>"+
			"<form id='form_vote' action='' style='position:absolute;left:0px;top:0px;'>"+
			"<span style='color:#b3abb2'>Theme:</span><input type = 'text' id = 'theme' autocomplete='off'><br>"+
			"<span style='color:#b3abb2'>Option 1:</span><input type = 'text' id = 'a1' autocomplete='off'><br>"+
			"<span style='color:#b3abb2'>Option 2:</span><input type = 'text' id = 'a2' autocomplete='off'><br>"+
			"<span style='color:#b3abb2'>Option 3:</span><input type = 'text' id = 'a3' autocomplete='off'><br>"+
			"<button>confirm</button><button id = 'cancel'>cancel</button></form></div>"
}