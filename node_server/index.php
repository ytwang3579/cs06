<?php
   session_start();
?>

<!doctype html>
<html>
  <head>	
	<script src="resource/socket.io.js"></script>
	<script src="resource/create_vote.js"></script>
	<script src="resource/test_input.js"></script>
	<script src="https://code.jquery.com/jquery-1.11.1.js"></script>    
	<script src="https://kit.fontawesome.com/df3e0e4f87.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="./style.css">
  </head>
  
  <body>
	<div id="broadcast" class="broadcast">
	   <i class="fas fa-broadcast-tower"></i>
	   <i class="fas fa-broadcast-tower"></i>
	   <i class="fas fa-broadcast-tower"></i>
	   <span id="broadcast_message"></span>
    </div>
	<button type="button" class="btn btn-primary btn-sm" id="vote">vote</button>
    <button type="button" class="btn btn-secondary btn-sm" id="show_delete">Edit</button>
	<script>
		$('#show_delete').click(function(){
			if($('.delete').css("display") != "none" )
				$('.delete').hide();
			else	
				$('.delete').show();
		});
	</script>

	<ul id="messages" class = "messages"></ul>
	<div class="ff">
    <form  id = "ff" action="" class="input-group mb-3">
	   <input id="m" autocomplete="off" type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="button-addon2">
	   <button class="btn btn-outline-success my-2 my-sm-0">Send</button>
	</form>
	</div>

	<script>
		var room = window.parent.now_room;
		var user_id = "<?php echo $_SESSION['id']; ?>";
	  $(function () {
		var socket = io('http://cs06.2y.cc:25565');
		socket.emit('join', room);
		
		//send message
		$('#ff').submit(function(e){
		  e.preventDefault(); // prevents page reloading
		  if($('#m').val() != ''){
			socket.emit('chat message', $('#m').val(), window.parent.name, room, "<?=  $_SESSION['picture'] ?>");//emit chat message
			$('#m').val('');
		  }
		  return false;
		});
		
		socket.on('chat message', function(idx, msg, name, time_string, picture){//receive server pushed message
		  if(msg != ''){
			var new_message = get_chat_message_div(idx, msg, name, time_string, picture, socket);
			$('#messages').append(new_message);//append new message and scroll down
			window.scroll(0,document.body.scrollHeight);
		  }
		});
		
		  socket.on('delete_message', function(idx){
			$('#'+idx+'').remove();
		  });
		
		$('#vote').click(function(){ //deal with create vote when click vote button
			var vote_area =  create_vote_area();
			$('#vote').after(vote_area);
			vote_area = $('#vote_area');
			$('#form_vote').submit(function(e){//when submit the create vote form
				e.preventDefault(); // prevents page reloading
				//do check input
				var ok=1;
				if($('#theme').val() == ''){// theme cannot be empty
					if($('#theme_err').length == 0){
						$('#theme').after('<span id="theme_err" style="color:red">theme cannot be empty</span>');
					}
					ok=0;
				}
				
				//test if we have at least two choice
				var ans = test_ans( $('#a1').val(),  $('#a2').val(),  $('#a3').val() );
				
				if(ans.a_total < 2){
					if($('#a_err').length == 0){
						$('#cancel').after('<span id="a_err" style="color:red">There must be at least two choice</span>');
					}
					ok= 0;
				}
				
				if(ok){
					socket.emit('create vote', $('#theme').val(), ans.a_array, window.parent.name, room, "<?=  $_SESSION['picture'] ?>");
					vote_area.fadeOut('normal', function(){
						vote_area.remove();
					});
				}
			});
			$('#cancel').click(function(e){
				e.preventDefault();
				vote_area.fadeOut('normal', function(){
					vote_area.remove();
				});
			});
		});
		
		socket.on('broadcast message', function(msg, name, time_string){
			//do admin brroadcast
		  $('#broadcast_message').text(msg+'    '+time_string);
		  $('#broadcast').show();
		});
		
		socket.on('vote message', function(idx, vote_object, name, time_string){ //receive vote message
			vote_object['index'] = idx;
			var is_voted = false;
			vote_object["voted"].forEach(function(item){
				if(item == user_id) is_voted = true;
			});
			var new_message = get_chat_message_div(idx, vote_object, name, time_string, vote_object.picture, socket);
			
			$('#messages').append(new_message);//append new message and scroll down
			window.scroll(0,document.body.scrollHeight);
			$('#'+vote_object.theme+'_'+vote_object.index).submit(function(e){//Do vote 
				e.preventDefault();
				
				if($('input[name=options_'+vote_object.index+']:checked').length > 0){
					var voted = $('input[name=options_'+vote_object.index+']:checked').val();
					vote_object[voted]++;
					vote_object['voted'].push(user_id);
					
					socket.emit('vote update', vote_object, room, name, time_string);
				}
			});
		});
		
		socket.on('vote update', function(vote_object, name, time_string){	//receive vote update
			console.log(vote_object);
			var new_message = get_chat_message_div(vote_object.index, vote_object, name, time_string, vote_object.picture, socket);
			var old_vote_message = $('#'+vote_object.index);
			old_vote_message.after(new_message);
			old_vote_message.remove();
		});
		
		socket.on('disconnect', function(){
			console.log('server is down.');
		});
		
		socket.on('reconnect',function(){
			socket.emit('rejoin', room);
		});
	  });
	  
	  function is_tag(data){//return if user is taged
	    var at_position=-1;
		var name_taged;
		for(var i=0; i<data.length; i++){
		  if(at_position == -1){
			if(data[i] == '@'){
			  at_position = i;
			}
		  }
		  else{
		    if(data[i] == ' '){
				name_taged = data.slice(at_position+1, i);
				console.log(name_taged);
				if(name_taged == window.parent.name)	return true;
			}
			else if(i == data.length-1){
				if(data.slice(at_position+1, i+1) == window.parent.name )	return true;
			}
		  }
		}
		
		return false;
	  }

		function create_new_message_div(name, msg, time_string, picture){//create new message element
			//<div  class="message">
        		//<img class="message-image" src="%USER_IMAGE_URL%">
        		//<a class="sender">Leo Wang</a>
        		//<a class="message-timestamp">16:02</a>
        
        		//<span class="message-content" >
            		//<div class="text">fasfdfsafas</div>
        		//</span>
			//</div>

			var new_message = $('<li>');
			
			var div = $('<div>').toggleClass('message');
			
			var img = $('<img>').toggleClass('message-image');
			img.attr('src', picture);
			
			var a_name = $('<a>').text(name);
			a_name.toggleClass('sender');
			
			var a_time = $('<a>').text(time_string);
			a_time.toggleClass('message-timestamp');

			var span_message = $('<span>').toggleClass('message_content');
			
			var div_message;
			if(typeof(msg) == 'string')	div_message = $('<div>').text(msg);
			else div_message = vote_object_to_div(msg);
			
			div_message.toggleClass('text');
			span_message.append(div_message);

			div.append(img);
			div.append(a_name);
			div.append(a_time);
			div.append(span_message);

			new_message.append(div);

			return new_message;			
		}
		
		function get_chat_message_div(idx, msg, name, time_string, picture, socket){
		  var new_message = create_new_message_div(name, msg, time_string, picture);
		  new_message.attr('id', idx);
			
		  if( name == window.parent.name ){//add delete button if sender is yourself
			  var delete_button = $('<button>').text("delete");//add delete button
			  delete_button.toggleClass("delete");
			  delete_button.hide();
			  delete_button.click(function(){
    				//send remove message to server
				  socket.emit('delete_message', idx, room);
  			  });
			  new_message.append(delete_button);//append delete button to new_message
		  }

		  if(is_tag(msg)){//do hightlight
			new_message.css("background", "#fdfdba");
		  }
		  
		  return new_message;
		}
		
		function vote_object_to_div(data){
			if(typeof(data) != 'object')	return;
			
			var is_voted = false;
			data["voted"].forEach(function(item){
				if(item == user_id) is_voted = true;
			});
			
			var vote_div = $('<div>');
			
			var theme = $('<p>').text(data.theme);
			var vote_table = $('<table>');
			var tmp_tr = $('<tr>');
			tmp_tr.append($('<th>').text("Options"));
			tmp_tr.append($('<th>').text("Vote number"));
			vote_table.append(tmp_tr);
			
			data.options.forEach(function(item){
				if(!is_voted){
					var vote_input = $('<input>');
					vote_input.attr('id', item+"_"+data.index);
					vote_input.attr('type', 'radio');
					vote_input.attr('form', data.theme+"_"+data.index);
					vote_input.attr('name', 'options_'+data.index);
					vote_input.attr('value', item);
				}
				tmp_tr = $('<tr>');
				tmp_tr.append($('<td>').text(item));
				tmp_tr.append($('<td>').text(data[item]));
				if(!is_voted) tmp_tr.append($('<td>').append(vote_input));
				vote_table.append(tmp_tr);
			});
			
			var vote_form = $('<form>');
			vote_form.attr('id', data.theme+"_"+data.index);//add form id
			vote_div.append(theme);
			vote_form.append(vote_table);
			if(!is_voted) vote_form.append($('<button>').text('send'));
			vote_div.append(vote_form);
			
			return vote_div;
			
		}
	</script>
  </body>
</html>

