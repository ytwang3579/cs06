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

io.on('connection', function(socket){
  console.log('a user connected');
  socket.on('chat message', function(msg, name, room, picture){
	 var d = new Date();
	 var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	 var time_string = d.toLocaleTimeString("zh-TW", opt);
	 if(room == ''){
		io.emit('chat message', msg, name, time_string);
	 }
	 else{
	    var silence=0;
	    var sql = "SELECT silence FROM user_list WHERE name =?";
		con.query(sql,[name] ,function (err, result) {
		  if (err) throw err;
		  console.log(result[0].silence);
		  if(result[0].silence==1) silence=1;
		  
		  if(silence==0){
	 	    
			var tmp = {};
			tmp['picture'] = picture;
		    var sql = "INSERT INTO "+room +" (sender, content, time, vote) VALUES (?, ?, ?, ?)";
		    con.query(sql, [name, msg, time_string , JSON.stringify(tmp)], function (err, result) {
		        if (err) throw err;
		        console.log("1 record inserted");
				io.in(room).emit('chat message', result.insertId, msg, name, time_string, picture);
		    });
	      }
	    });
	    
        }
  });
  
  socket.on('join', function(room, user_id){
          //console.log("join");
	  if(room!="" && room!=null){
	  var sql = "SELECT * FROM " + room;
	  con.query(sql, function (err, result) {
		if (err) throw err;
		console.log("selected");
		result.forEach(function(item){
			var vote_object = JSON.parse(item.vote);
			if(item.content != ''){
				socket.emit('chat message', item.idx, item.content, item.sender, item.time, vote_object.picture);
			}
			else{
				socket.emit('vote message', item.idx, vote_object, item.sender, item.time);
				console.log('vote');
			}
			
			if(item.vote == null){
				console.log('null');
			}
		});
			
		
	  });
	  socket.join(room);
	  
	  sql = "SELECT private FROM " + user_id + "_chatlist WHERE chat_room_name = ?";
	  con.query(sql, [room], function (err, result){
		  if (err) throw err;
		  
		  socket.emit("is private", result[0].private);
	  });
	  
	  }
  });
  
  socket.on('create vote', function(theme, ans, name, room, picture){
	  var vote_object = {};
	  ans.forEach(function(item){
		  vote_object[item] = 0;
	  });
	  vote_object['voted'] = [''];
	  vote_object['theme'] = theme;
	  vote_object['options'] = ans;
	  vote_object['picture'] = picture;
	  
	  //TODO add index. insert into mysql
	  vote_object['index'] = '100';
	  
	  var d = new Date();
	  var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	  var time_string = d.toLocaleTimeString("zh-TW", opt);
	  
	  var sql = "INSERT INTO "+room +" (sender, content, time, vote) VALUES (?, ?, ?, ?)";
		con.query(sql, [name, "", time_string , JSON.stringify(vote_object)], function (err, result) {
			if (err) throw err;
			console.log("vote record inserted");
			io.in(room).emit('vote message', result.insertId, vote_object, name, time_string);	
	  });
	  
  });
  
  socket.on('vote update', function(vote_object, room, name, time_string){
	  var sql = "UPDATE "+ room +" SET vote = ? WHERE idx = ?";
		con.query(sql, [JSON.stringify(vote_object), vote_object.index], function (err, result) {
			if (err) throw err;
			console.log("vote record updated");
			io.in(room).emit('vote update', vote_object, name, time_string);	
	  });
  });
  
  socket.on('rejoin', function(room){
	  socket.join(room);
  });
  
  socket.on('broadcast message', function(msg){
	  var d = new Date();
	  var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	  var time_string = d.toLocaleTimeString("zh-TW", opt);
	  io.emit('broadcast message', msg, 'Admin', time_string);
  });
  
  socket.on('delete_message', function(idx, room){
    var sql = "DELETE FROM "+room+" WHERE idx = ?";
	  con.query(sql, idx, function (err, result) {
		if (err) throw err;
		io.in(room).emit('delete_message', idx);
	  });
	  
  });
	 
  socket.on('disconnect', function(){
    console.log('user disconnected');
  });
});

http.listen(25565, function(){
  console.log('listening on *:25565');
});