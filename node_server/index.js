var app = require('express')();
var http = require('http').createServer(app);
var io = require('socket.io')(http);

var mysql = require('mysql');

var con = mysql.createConnection({
  host: "localhost",
  user: "cs06",
  password: "Hbds43RV5",
  database: "cs06"
});

con.connect(function(err) {
	if (err) throw err;
	console.log("sql Connected!");
});

//when a user connect
io.on('connection', function(socket){
  console.log('a user connected');
  
  //when user send message tell other in same room, and add to sql
  socket.on('chat message', function(msg, name, room, picture){
	 //get now time
	 var time_string = get_now_time();
	 if(room == ''){
		console.log('an empty room?');
	 }
	 else{
		//deal with insert message into sql, first check if user has been banned
	    var silence=0;
	    var sql = "SELECT silence FROM user_list WHERE name =?";
		con.query(sql,[name] ,function (err, result) {
		  if (err) throw err;
		  if(result[0].silence==1) silence=1;
		  
		  //when user is not banned, send message and insert into sql
		  if(silence==0){
	 	    
			var tmp = {};
			tmp['picture'] = picture;
		    var sql = "INSERT INTO "+room +" (sender, content, time, vote) VALUES (?, ?, ?, ?)";
		    con.query(sql, [name, msg, time_string , JSON.stringify(tmp)], function (err, result) {
		        if (err) throw err;
		        //console.log("1 record inserted");
				io.in(room).emit('chat message', result.insertId, msg, name, time_string, picture);
		    });
	      }
	    });
	    
     }
  });
  
  //deal with join request:send chat history and join room and query if room is private
  socket.on('join', function(room, user_id){
      
	  if(room!="" && room!=null){
	  var sql = "SELECT * FROM " + room;
	  con.query(sql, function (err, result) {
		if (err) throw err;
		
		//send chat history
		result.forEach(function(item){
			var vote_object = JSON.parse(item.vote);
			if(item.content != ''){
				socket.emit('chat message', item.idx, item.content, item.sender, item.time, vote_object.picture);
			}
			else{
				socket.emit('vote message', item.idx, vote_object, item.sender, item.time);
			}
			
		});
	  });
	  socket.join(room);
	  
	  //query if room is private
	  sql = "SELECT private FROM " + user_id + "_chatlist WHERE chat_room_name = ?";
	  con.query(sql, [room], function (err, result){
		  if (err) throw err;
		  
		  socket.emit("is private", result[0].private);
	  });
	  
	  }
  });
  
  //create vote object according user send data
  socket.on('create vote', function(theme, ans, name, room, picture){
	  var vote_object = {};
	  ans.forEach(function(item){
		  vote_object[item] = 0;
	  });
	  vote_object['voted'] = [''];
	  vote_object['theme'] = theme;
	  vote_object['options'] = ans;
	  vote_object['picture'] = picture;
	  vote_object['index'] = 10000;
	  
	  //get now time
	  var time_string = get_now_time();
	  
	  var sql = "INSERT INTO "+room +" (sender, content, time, vote) VALUES (?, ?, ?, ?)";
		con.query(sql, [name, "", time_string , JSON.stringify(vote_object)], function (err, result) {
			if (err) throw err;
			io.in(room).emit('vote message', result.insertId, vote_object, name, time_string);	
	  });
	  
  });
  
  //deal with  vote update
  socket.on('vote update', function(vote_object, room, name, time_string){
	  var sql = "UPDATE "+ room +" SET vote = ? WHERE idx = ?";
		con.query(sql, [JSON.stringify(vote_object), vote_object.index], function (err, result) {
			if (err) throw err;
			//console.log("vote record updated");
			io.in(room).emit('vote update', vote_object, name, time_string);	
	  });
  });
  
  //deal with rejoin room
  socket.on('rejoin', function(room){
	  socket.join(room);
  });
  
  //deal with broadcast
  socket.on('broadcast message', function(msg){
	  var d = new Date();
	  var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	  var time_string = d.toLocaleTimeString("zh-TW", opt);
	  io.emit('broadcast message', msg, 'Admin', time_string);
  });
  
  //deal with delete message
  socket.on('delete_message', function(idx, room){
    var sql = "DELETE FROM "+room+" WHERE idx = ?";
	  con.query(sql, idx, function (err, result) {
		if (err) throw err;
		io.in(room).emit('delete_message', idx);
	  });
	  
  });
  
  //when user disconnect console log it
  socket.on('disconnect', function(){
    console.log('user disconnected');
  });
});

//listen on port 25565
http.listen(25565, function(){
  console.log('listening on *:25565');
});

function get_now_time(){//return now time
	var d = new Date();
	var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	var time_string = d.toLocaleTimeString("zh-TW", opt);
	return time_string;
}