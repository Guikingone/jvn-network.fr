var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get('/communaute', function(req, res){
    res.sendFile("%kerner.root.dir" + 'app/Resources/views/Communaute/index.html.twig');
});

io.on('connection', function(socket){
    console.log('a user connected');
});

http.listen(80, function(){
    console.log('listening on *:80');
});