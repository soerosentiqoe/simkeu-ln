<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title> -SPM  </title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="{{ asset('/template/img/favicontil.ico')}}" rel="icon" type="image/x-icon" />
	<link href="{{ asset('/template/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/template/font-awesome-4.2.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/template/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/template/css/datepicker/datepicker3.css') }}" rel="stylesheet" type="text/css" />
	
	<!-- Plugins -->
	<link href="{{ asset('/plugins/datatables/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/plugins/chosen/chosen.min.css') }}" rel="stylesheet">
	<link href="{{ asset('/plugins/alertify/themes/alertify.core.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/plugins/alertify/themes/alertify.default.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/plugins/pekeupload/css/custom.css') }}" rel="stylesheet">
	<link href="{{ asset('/plugins/jquery-timepicker/jquery.timepicker.css') }}" rel="stylesheet" />
	
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
					<!-- User Account: style can be found in dropdown.less -->
					<li>
						<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">
							<span>Tahun Anggaran <?php echo $info_tahun; ?></span>
						</a>
					</li>
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="glyphicon glyphicon-user"></i>
							<span id="info-username"><?php echo $info_username; ?></span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header bg-green">
								<img src="../data/user/<?php echo $info_foto; ?>" class="img-circle" alt="User Image" />
								<p id="info-user">
									<?php echo $info_nama; ?>
									<br>
									<?php echo $info_email; ?>
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-right">
									<a href="logout" class="btn btn-danger btn-flat">Logout</a>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</nav>
	</header>
	<div class="wrapper row-offcanvas row-offcanvas-left">
		
		<!-- Small boxes (Stat box) -->		
		<div class="row">
			<div class="col-lg-12">
				
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">
							<a href="javascript:;" id="refresh" class="btn btn-info"><i class="fa fa-refresh"></i></a>
							 Tanggal Antrian 
							<input type="text" id="tgl_antrian" name="tgl_antrian" class="dp">
							<select id="kdloket" name="kdloket" class="chosen">
								<option value="" style="display:none;">Pilih Loket</option>
							</select>
						</h3>
						<div class="col-lg-2 pull-right">
							
						</div>
					</div><!-- /.box-header -->
					<div class="box-body table-responsive" style="overflow-x:scroll;" id="tabel-antrian">
						
					</div><!-- /.box-body -->
				</div><!-- /.box -->
				
			</div>
		</div>
		
	</div><!-- ./wrapper -->
	
	<script src="{{ asset('/template/js/jquery.min.js') }}"></script>
	
	<!-- Primary -->
	<script src="{{ asset('/template/js/bootstrap.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/template/js/jquery-ui.min.js') }}" type="text/javascript"></script>
   
	<!-- Plugins -->
	<script src="{{ asset('/plugins/datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/plugins/chosen/chosen.jquery.min.js') }}"></script>
	<script src="{{ asset('/plugins/alertify/lib/alertify.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/datatables/js/jquery.dataTables.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/datatables/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/plugins/jquery-timepicker/jquery.timepicker.js') }}"></script>

	<!-- AdminLTE App -->
	<script src="{{ asset('/template/js/AdminLTE/app.js') }}" type="text/javascript"></script>
	
	<script>
		jQuery(document).ready(function(){
			jQuery('#kdloket').chosen();
			
			//init datepicker/datepicker3
			jQuery('#tgl_antrian').datepicker({
				format: 'dd-mm-yyyy',
				autoclose:true
			});
			
			//cari loket
			function get_loket(tgl_antrian){
				jQuery.get('antrian-spm/loket/'+tgl_antrian, function(result){
					if(result){
						jQuery('#kdloket').html(result).trigger('chosen:updated');
					}
				});
			}
			
			//cari tgl antrian default
			function get_tanggal(){
				jQuery.get('antrian-spm/tanggal', function(result){
					if(result){
						jQuery('#tgl_antrian').val(result);
						var tgl_antrian=result.replace(/-/g,'');
						get_loket(tgl_antrian);
					}
				});
			}
			
			//cari data antrian
			function get_antrian(tgl_antrian,kdloket){
				jQuery.get('antrian-spm/data/'+tgl_antrian+'/'+kdloket, function(result){
					if(result){
						jQuery('#tabel-antrian').html(result);
					}
				});
			}
			
			//ubah tanggal antrian
			jQuery('#tgl_antrian').change(function(){
				var tgl_antrian=jQuery(this).val();
				var tgl_antrian=tgl_antrian.replace(/-/g,'');
				get_loket(tgl_antrian);
			});
			
			//ubah data kode loket
			jQuery('#kdloket').change(function(){
				var kdloket=jQuery(this).val();
				var tgl_antrian=jQuery('#tgl_antrian').val();
				tgl_antrian=tgl_antrian.replace(/-/g,'');
				get_antrian(tgl_antrian,kdloket);
			});
			
			//init default
			get_tanggal();
			
			//tombol refresh
			jQuery('#refresh').click(function(){
				if(jQuery('#tgl_antrian').val()!='' && jQuery('#kdloket').val()!=''){
					var tgl_antrian=jQuery('#tgl_antrian').val();
					var kdloket=jQuery('#kdloket').val();
					tgl_antrian=tgl_antrian.replace(/-/g,'');
					get_antrian(tgl_antrian,kdloket);
				}
				else{
					alertify.log('Pilih tanggal dan loket antrian terlebih dahulu.');
				}
			});
			
		});
	</script>
	
</body>
</html>