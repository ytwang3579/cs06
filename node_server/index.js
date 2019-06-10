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

app.get('/*', function(req, res){
  //res.send('<h1>Hello world</h1>');
  //res.sendfile('/export/home/team6/public_html/node_server/index.html');
});

io.on('connection', function(socket){
  console.log('a user connected');
  socket.on('chat message', function(msg, name, room){
	 var d = new Date();
	 var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	 var time_string = d.toLocaleTimeString("zh-TW", opt)
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
	 	    io.in(room).emit('chat message', msg, name, time_string);

		    var sql = "INSERT INTO "+room +" (sender, content, time) VALUES (?, ?, ?)";
		    con.query(sql, [name, msg, time_string ], function (err, result) {
		        if (err) throw err;
		        console.log("1 record inserted");
		    });
	          }
	    });
	    
	    console.log(silence);
	    
        }
  });
  
  socket.on('join', function(room){
          //console.log("join");
	  if(room!=null){
	  var sql = "SELECT * FROM " + room;
	  con.query(sql, function (err, result) {
		if (err) throw err;
		console.log("selected");
		result.forEach(function(item){
			socket.emit('chat message', item.content, item.sender, item.time);
		});
			
		
	  });
	  socket.join(room);
	  }
  });
  
  socket.on('voting', function(theme, a1, a2, a3){
	  console.log([theme, a1, a2, a3]);
  });
  
  socket.on('rejoin', function(room){
	  socket.join(room);
  });
  
  socket.on('broadcast message', function(msg){
	  var d = new Date();
	  var opt = {hour:"2-digit", minute:"2-digit", hour12:false};
	  var time_string = d.toLocaleTimeString("zh-TW", opt)
	  io.emit('chat message', msg, 'Admin', time_string);
  });
	 
  socket.on('disconnect', function(){
    console.log('user disconnected');
  });
});

http.listen(25565, function(){
  console.log('listening on *:25565');
});