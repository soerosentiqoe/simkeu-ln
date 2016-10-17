var socket = require( 'socket.io' );
var express = require( 'express' );
var http = require( 'http' );

var app = express();
var server = http.createServer( app );

var io = socket.listen( server );

io.sockets.on( 'connection', function( client ) {
	console.log( "New client !" );
	
	client.on( 'message', function( data ) {
		var current = new Date();
		var tanggal = current.getDate();
		var bulan = current.getMonth();
		var tahun = current.getFullYear();
		var jam = current.getHours();
		var menit = current.getMinutes();
		var detik = current.getSeconds();
		var waktu = tanggal+'/'+bulan+'/'+tahun+' '+jam+':'+menit+':'+detik;
		//console.log( 'Message received : ' + data.message );
		
		//client.broadcast.emit( 'message', { name: data.name, message: data.message } );
		io.sockets.emit( 'message', { name: data.name, image: data.image, message: data.message, time: waktu } );
	});
});

server.listen( 8080 );