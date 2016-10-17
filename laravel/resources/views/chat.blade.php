<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title> e-SPM - Chat </title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="{{ asset('/template/img/favicon.ico')}}" rel="icon" type="image/x-icon" />
	<link href="{{ asset('/template/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/template/font-awesome-4.2.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/template/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
	
	<!-- Plugins -->
	<link href="{{ asset('/plugins/alertify/themes/alertify.core.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/plugins/alertify/themes/alertify.default.css') }}" rel="stylesheet" type="text/css" />
	
	<!-- Theme style -->
	<link href="{{ asset('/template/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />
	
</head>
<style>
	th{
		text-align:center;
	}
</style>
<body class="skin-blue">
	<!-- header logo: style can be found in header.less -->
	<header class="header">
		<a href="javascript:;" class="logo">
			<!-- Add the class icon to your logo image or logo icon to add the margining -->
			<img src="{{ asset('/template/img/H_Logo.png')}}" width="102" height="36">
		</a>
		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top" role="navigation">
			
			<div class="navbar-right">
				<ul class="nav navbar-nav">
					
				</ul>
			</div>
		</nav>
	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		
		<!-- Small boxes (Stat box) -->		
		<div class="row">
			<div style="overflow-y:scroll;">
				<div class="col-lg-12">
					<div id="chat-body" class="container"></div>
				</div>
			</div>
			<div class="col-lg-12 well" style="position:fixed;bottom:0px;left:0px;right:0px;">
				<form id="form-chat" name="form-chat" onsubmit="return false">
					<textarea id="chat-text" class="form-control"></textarea>
					<button id="submit" type="submit" class="btn bg-blue">Send</button>
				</form>
			</div>
		</div>
		
	</div><!-- ./wrapper -->
	
	<script src="{{ asset('/template/js/jquery.min.js') }}"></script>
	
	<!-- Primary -->
	<script src="{{ asset('/template/js/bootstrap.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/template/js/jquery-ui.min.js') }}" type="text/javascript"></script>
   
	<!-- Plugins -->
	<script src="{{ asset('/plugins/alertify/lib/alertify.min.js') }}" type="text/javascript"></script>
	
	<!-- AdminLTE App -->
	<script src="{{ asset('/template/js/AdminLTE/app.js') }}" type="text/javascript"></script>
	
	<!-- Socket IO -->
	<script src="{{ asset('/nodejs/node_modules/socket.io/node_modules/socket.io-client/dist/socket.io.js') }}"></script>
	
	<script>
		jQuery(document).ready(function(){
			
			var socket = io.connect( '<?php echo $host; ?>:8080' );
			
			jQuery('#submit').click(function(){
				if(jQuery('#chat-text').val()!='') {
					var msg = jQuery('#chat-text').val();
					var name = '<?php echo $nama; ?>';
					var image = '<?php echo $foto; ?>';
					socket.emit( 'message', { name: name, image: image, message: msg } );
					jQuery('#chat-text').val('');
				}
			});
			
			socket.on( 'message', function( data ) {
				var actualContent = jQuery( "#chat-body" ).html();
				var newMsgContent = '<div class="media">'+
										'<a class="pull-left" href="#">'+
											'<img class="media-object" src="data/user/'+data.image+'" width=50 height=65 alt="Foto Profile">'+
										'</a>'+
										'<div class="media-body">'+
											'<h4 class="media-heading">'+data.name+'</h4>'+
											'<h5>'+data.time+'</h5>'+
											data.message+
										'</div>'+
									'</div>'+
									'<hr>'
				var content = newMsgContent + actualContent;
				jQuery( "#chat-body" ).html( content );
			});
			
		});
	</script>
</body>
</html>