<!doctype html>
<html>
  <head>	
	<script src="resource/socket.io.js"></script>
	<script src="resource/create_vote.js"></script>
	<script src="resource/test_input.js"></script>
	<script src="https://code.jquery.com/jquery-1.11.1.js"></script>    
	<script src="https://kit.fontawesome.com/df3e0e4f87.js"></script>
	<link rel="stylesheet" type="text/css" href="./style.css">
  </head>
  
  <body>
	<div id="broadcast" class="broadcast">
	   <i class="fas fa-broadcast-tower"></i>
	   <i class="fas fa-broadcast-tower"></i>
	   <i class="fas fa-broadcast-tower"></i>
	   <span id="broadcast_message"></span>
    </div>
    <button id='vote'>vote</button>
	<button id='show_delete'>Edit</button>
	<script>
		$('#show_delete').click(function(){
			if($('.delete').css("display") != "none" )
				$('.delete').hide();
			else	
				$('.delete').show();
		});
	</script>

    <ul id="messages" class = "messages"></ul>
    <form id = "ff" action="" class="ff">
      <input id="m" autocomplete="off" /><button>Send</button>
    </form>

	<script>
		var room = window.parent.now_room;
	  $(function () {
		var socket = io('http://cs06.2y.cc:25565');
		socket.emit('join', room);
		
		//send message
		$('#ff').submit(function(e){
		  e.preventDefault(); // prevents page reloading
		  socket.emit('chat message', $('#m').val(), window.parent.name, room);//emit chat message
		  $('#m').val('');
		  return false;
		});
		
		socket.on('chat message', function(idx, msg, name, time_string){//receive server pushed message
		  add_new_chat_message(idx, msg, name, time_string);
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
					socket.emit('create vote', $('#theme').val(), ans.a_array, window.parent.name, room);
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
		  console.log('start');
		  $('#broadcast_message').text(msg+'    '+time_string);
		  $('#broadcast').show();
		  console.log('finish');
		});
		
		socket.on('vote message', function(vote_object, name, time_string){
			add_new_chat_message('100', JSON.stringify(vote_object), name, time_string);
			
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

		function create_new_message_div(name, msg, time_string){//create new message element
			//<div  class="message">
        		//<img class="message-image" src="%USER_IMAGE_URL%">
        		//<a class="sender">Leo Wang</a>
        		//<a class="message-timestamp">16:02</a>
        
        		//<span class="message-content" >
            		//<div class="text">fasfdfsafas</div>
        		//</span>
			//</div>
			document.cookie = 'msgname="' + name + '";';
			var new_message = $('<li>');
			
			var div = $('<div>').toggleClass('message');
<?php
	require("../config.php");
	$dsn='mysql:host='.$CFG['mysql_host'].';dbname='.$CFG['mysql_dbname'].';';
	try {
		$dbh = new pdo($dsn, $CFG['mysql_username'],$CFG['mysql_password']);
	}catch (pdoexception $e) {
		echo 'connection failed: '.$e->getmessage();
	}

	$sth=$dbh->prepare('select picture from user_list where name = '.$_COOKIE['msgname'].';');
	$sth->execute();
	
	var_dump($sth->fetch());

	$dsn = null;
?>
			var img = $('<img>').toggleClass('message-image');
			img.attr('src', '%USER_IMAGE_URL%');
			
			var a_name = $('<a>').text(name);
			a_name.toggleClass('sender');
			
			var a_time = $('<a>').text(time_string);
			a_time.toggleClass('message-timestamp');

			var span_message = $('<span>').toggleClass('message_content');

			var div_message = $('<div>').text(msg);
			div_message.toggleClass('text');
			span_message.append(div_message);

			div.append(img);
			div.append(a_name);
			div.append(a_time);
			div.append(span_message);

			new_message.append(div);

			return new_message;			
		}
		
		function add_new_chat_message(idx, msg, name, time_string){
		  var new_message = create_new_message_div(name, msg, time_string);
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
		  $('#messages').append(new_message);//append new message and scroll down
		  window.scroll(0,document.body.scrollHeight);
		}
	</script>
  </body>
</html>
