<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
	<title><?php echo $app_nama.' v.'.$app_versi; ?> Registrasi User</title>
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
	<link href="{{ asset('/plugins/formwizard/css/components.css') }}" rel="stylesheet" />
	<link href="{{ asset('/plugins/formwizard/css/plugins.css') }}" rel="stylesheet" />
	<link href="{{ asset('/plugins/formwizard/css/layout.css') }}" rel="stylesheet" />
	<link href="{{ asset('/plugins/formwizard/css/select2.css') }}" rel="stylesheet" />
	
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
					<li>
						<a href="login">
							Kembali ke halaman login...
						</a>
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
						
					</div><!-- /.box-header -->
					<div class="box-body">
										
						<form class="form-horizontal" id="form-ruh" name="form-ruh">
							<input type="hidden" id="inp-id" name="inp-id">
							<input type="hidden" id="inp-rekambaru" name="inp-rekambaru">
							<input type="hidden" id="inp-kdkppn" name="inp-kdkppn">
							<input type="hidden" name="_token" value="<?php echo $registrasi_token; ?>" />	
					
							<div class="page-content-wrapper">
								<!-- BEGIN PAGE CONTENT-->
								<div class="row">
									<div class="col-md-12">
										<div class="portlet box blue" id="form_wizard_1">
											<div class="portlet-title">
												<div class="caption">
													<i class="fa fa-user"></i> Form Registrasi User Operator Satker - <span class="step-title">
													Step 1 of 6 </span>
												</div>
												<div class="tools hidden-xs">
													<a href="javascript:;" class="collapse">
													</a>
													
												</div>
											</div>
											<div class="portlet-body form">											
												<div class="form-wizard">
														<div class="form-body">
															<ul class="nav nav-pills nav-justified steps">
																<li>
																	<a href="#tab1" data-toggle="tab" class="step active">
																	<span class="number">
																	1 </span>
																	<span class="desc">
																	<i class="fa fa-check"></i> Data KPPN </span>
																	</a>
																</li>
																<li>
																	<a href="#tab2" data-toggle="tab" class="step">
																	<span class="number">
																	2 </span>
																	<span class="desc">
																	<i class="fa fa-check"></i> Data Satker </span>
																	</a>
																</li>
																<li>
																	<a href="#tab3" data-toggle="tab" class="step">
																	<span class="number">
																	3 </span>
																	<span class="desc">
																	<i class="fa fa-check"></i> Data User </span>
																	</a>
																</li>
																<li>
																	<a href="#tab4" data-toggle="tab" class="step">
																	<span class="number">
																	4 </span>
																	<span class="desc">
																	<i class="fa fa-check"></i> Data Login </span>
																	</a>
																</li>
																<li>
																	<a href="#tab5" data-toggle="tab" class="step">
																	<span class="number">
																	5 </span>
																	<span class="desc">
																	<i class="fa fa-check"></i> Upload </span>
																	</a>
																</li>
																<li>
																	<a href="#tab6" data-toggle="tab" class="step">
																	<span class="number">
																	6 </span>
																	<span class="desc">
																	<i class="fa fa-check"></i> Konfirmasi </span>
																	</a>
																</li>
															</ul>
															<div id="bar" class="progress progress-striped" role="progressbar">
																<div class="progress-bar progress-bar-success">
																</div>
															</div>
															<div class="tab-content">
																<div class="alert alert-danger display-none">
																	<button class="close" data-dismiss="alert"></button>
																	Masih terdapat kesalahan dalam pengisian form. Silahkan cek kolom dibawah.
																</div>
																<div class="alert alert-success display-none">
																	<button class="close" data-dismiss="alert"></button>
																	Validasi form berhasil! Silahkan dilanjutkan.
																</div>
																<div class="tab-pane active" id="tab1">
																	<div class="tab-content">
																		<div class="alert alert-warning">
																			Peringatan! Waktu registrasi dibatasi 10 menit. Apabila waktu pengisian sudah melebihi 10 menit, silahkan refresh halaman registrasi.
																		</div>
																	</div>
																	<br>
																	<div class="form-group" id="div-kppn">
																		<label class="control-label col-md-3">Pilih KPPN Anda</label>
																		<div class="col-md-6">
																			<select id="kdkppn" name="kdkppn" class="form-control"></select>
																		</div>
																	</div>																	
																</div>
																<div class="tab-pane" id="tab2">
																	<div class="form-group" id="div-satker">
																		<label class="control-label col-md-3">Kode Satker</label>
																		<div class="col-md-6">
																			<select id="kdsatker" name="kdsatker" class="form-control"></select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Nomor DIPA</label>
																		<div class="col-md-6">
																			<input type="text" id="nodipa" name="nodipa" class="form-control" placeholder="Masukkan Nomor DIPA" maxlength="255" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Tanggal DIPA</label>
																		<div class="col-md-4">
																			<input type="text" id="tgdipa" name="tgdipa" class="form-control val_num" placeholder="Masukkan Tanggal DIPA" autocomplete="off"/>
																		</div>
																	</div>
																	
																</div>
																<div class="tab-pane" id="tab3">
																	<div class="form-group">
																		<label class="control-label col-md-3">Nama</label>
																		<div class="col-md-6">
																			<input type="text" id="nama" name="nama" class="form-control val_char" placeholder="Masukkan Nama Lengkap" maxlength="50" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Jenis Pegawai</label>
																		<div class="col-md-4">
																			<select id="kdnip" name="kdnip" class="form-control" autocomplete="off">
																				<option style="display:none;" value="">Pilih Jenis Pegawai</option>
																				<option value="1">PNS</option>
																				<option value="2">TNI/Polri</option>
																				<option value="3">Non PNS/TNI/Polri</option>
																			</select>
																		</div>
																	</div>
																	<div class="form-group" id="div-nip">
																		<label class="control-label col-md-3">NIP/NRP</label>
																		<div class="col-md-6">
																			<input type="text" id="nip" name="nip" class="form-control val_num" maxlength="18" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group" id="div-nip2">
																		<label class="control-label col-md-3">NIP2 (NRP+Jabatan)</label>
																		<div class="col-md-6">
																			<input type="text" id="nip2" name="nip2" class="form-control" maxlength="255" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Jabatan Struktural</label>
																		<div class="col-md-6">
																			<input type="text" id="jabatan" name="jabatan" class="form-control" maxlength="255" placeholder="Masukkan Jabatan Struktural" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Email</label>
																		<div class="col-md-6">
																			<input type="text" id="email" name="email" class="form-control" maxlength="50" placeholder="jhon.doe@gmail.com" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Telp</label>
																		<div class="col-md-6">
																			<input type="text" id="telp" name="telp" class="form-control val_num" maxlength="20" placeholder="Masukkan Nomor Telepon" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Alamat</label>
																		<div class="col-md-6">
																			<textarea type="text" id="alamat" name="alamat" class="form-control" maxlength="255" placeholder="Masukkan Alamat Lengkap" autocomplete="off"></textarea>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Level User</label>
																		<div class="col-md-4">
																			<select id="kdlevel" name="kdlevel" class="form-control" autocomplete="off">
																				<option style="display:none;" value="">Pilih Level</option>
																				<option value="05">Pengantar SPM</option>
																				<option value="06">Bendahara</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="tab-pane" id="tab4">
																	<div class="form-group">
																		<label class="control-label col-md-3">Username</label>
																		<div class="col-md-4">
																			<input type="text" id="username" name="username" class="form-control" maxlength="50" placeholder="Masukkan Username untuk Login" autocomplete="off"/>
																		</div>
																		<div class="col-md-2">
																			<img src="template/img/ajax-loader.gif" id="loadingDiv">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Password</label>
																		<div class="col-md-4">
																			<input type="password" id="password" name="password" class="form-control" maxlength="50" placeholder="Masukkan Password untuk Login" autocomplete="off"/>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3">Password (Konfirmasi)</label>
																		<div class="col-md-4">
																			<input type="password" id="password1" name="password1" class="form-control" maxlength="50" placeholder="Konfirmasi Ulang Password untuk Login" autocomplete="off"/>
																		</div>
																	</div>
																</div>
																<div class="tab-pane" id="tab5">
																	<div class="form-group" id="div-upload-foto">
																		<label class="control-label col-md-3">Upload Foto User (*.png / max 1MB)</label>
																		<div class="col-md-6" id="div2-upload-foto">
																			<input type="file" id="upload3" name="upload3" class="btn btn-primary" autocomplete="off">
																		</div>
																	</div>
																	<div class="form-group" id="div-upload-sk">
																		<label class="control-label col-md-3">Upload SK Penunjukan (*.pdf / max 1MB)</label>
																		<div class="col-md-6" id="div2-upload-sk">
																			<input type="file" id="upload1" name="upload1" class="btn btn-primary" autocomplete="off">
																		</div>
																	</div>
																	<div class="form-group" id="div-upload-sd">
																		<label class="control-label col-md-3">Upload Surat Dispensasi (*.pdf / max 1MB)</label>
																		<div class="col-md-6" id="div2-upload-sd">
																			<input type="file" id="upload2" name="upload2" class="btn btn-primary" autocomplete="off">
																		</div>
																	</div>
																</div>
																<div class="tab-pane" id="tab6">
																	<div class="form-group">
																		<label class="control-label col-md-3">Disclaimer</label>
																		<div class="col-md-6">
																			<textarea type="text" id="disclaimer" name="disclaimer" class="form-control" maxlength="255"> Ini adalah disclaimer</textarea>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="control-label col-md-3"></label>
																		<div class="col-md-6">
																			<div class="checkbox-list">
																				<b>By submitting, I agree that all info entered was done accurately & truthfully.</b><br>
																				<input type="checkbox" name="agree[]" value="1" data-title="Agree" autocomplete="off"> I accept the <u>Terms and Conditions</u>
																			</div>
																			<div id="form_agree_error">
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="form-actions">
															<div class="row">
																<div class="col-md-offset-3 col-md-9">
																	<a href="javascript:;" class="btn default button-previous">
																	<i class="m-icon-swapleft"></i> Back </a>
																	<a href="javascript:;" class="btn blue button-next">
																	Continue <i class="m-icon-swapright m-icon-white"></i>
																	</a>
																	<a href="javascript:;" class="btn green button-submit">
																	Submit <i class="m-icon-swapright m-icon-white"></i>
																	</a>
																</div>
															</div>
														</div>
												</div>											
											</div>
										</div>
									</div>
								</div>
								<!-- END PAGE CONTENT-->
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTENT -->

<!-- END CONTAINER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../../assets/global/plugins/respond.min.js"></script>
<script src="../../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
	<script src="{{ asset('/template/js/jquery.min.js') }}"></script>
	<script src="{{ asset('/template/js/jquery-ui.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/template/js/bootstrap.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/plugins/pekeupload/js/pekeUpload.js') }}"></script>
	<script src="{{ asset('/plugins/datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
	<script type="text/javascript" src="{{ asset('/plugins/chosen/chosen.jquery.min.js') }}"></script>
	<script src="{{ asset('/plugins/alertify/lib/alertify.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/formwizard/js/jquery.validate.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/formwizard/js/jquery.bootstrap.wizard.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/formwizard/js/select2.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/formwizard/js/metronic.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/formwizard/js/form-wizard.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/plugins/formwizard/js/additional-methods.js') }}" type="text/javascript"></script>
	<script src="{{ asset('/template/js/AdminLTE/app.js') }}" type="text/javascript"></script>
	
	<script>
	
		(function (window) { // Prevent Cross-Frame Scripting attacks
			if (window.location !== window.top.location)
				window.top.location = window.location;
		})(this);

		jQuery(document).ready(function() {       
		   // initiate layout and plugins
		   Metronic.init(); // init metronic core components
		   FormWizard.init();
		   
			jQuery("body").off("keypress",'.val_char').on("keypress",'.val_char',function (e) {
				var charcode = e.which;
				if (
					(charcode === 8) || // Backspace
					(charcode === 13) || // Enter
					(charcode === 127) || // Delete
					(charcode === 32) || // Space
					(charcode === 0) || // arrow
					(charcode >= 65 && charcode <= 90) || // a - z
					(charcode >= 97 && charcode <= 122) // A - Z
					){ 
					console.log(charcode)
				}
				else {
					e.preventDefault()
					return
				}
			});	

			jQuery("body").off("keypress",'.val_name').on("keypress",'.val_name',function (e) {
				var charcode = e.which;
				if (
					(charcode === 8) || // Backspace
					(charcode === 13) || // Enter
					(charcode === 127) || // Delete
					(charcode === 32) || // Space
					(charcode === 0) || // arrow
					(charcode == 188) || // Koma
					(charcode == 190) || // Titik
					(charcode >= 65 && charcode <= 90) || // a - z
					(charcode >= 97 && charcode <= 122) // A - Z
					){ 
					console.log(charcode)
				}
				else {
					e.preventDefault()
					return
				}
			});	

			//hanya alpabet
			jQuery("body").off("keypress",'.val_num').on("keypress",'.val_num',function (e) {
				var charcode = e.which;
				if (
					(charcode === 8) || // Backspace
					(charcode === 13) || // Enter
					(charcode === 127) || // Delete
					(charcode === 0) || // arrow
					(charcode >= 48 && charcode <= 57)// 0 - 9
					){ 
					console.log(charcode)
				}
				else {
					e.preventDefault()
					return
				}
			});
					
			jQuery('#tgdipa').datepicker({
				format: 'dd-mm-yyyy',
				autoclose:true
			});
					
			jQuery('.chosen').chosen();	
			
			jQuery.get('kppn', function(result){
				if(result){
					jQuery('#kdkppn').html(result).trigger('chosen:updated');
				}
			});
					
			//upload sk
			function upload_foto(kdsatker){		
				jQuery("#upload3").pekeUpload({
					//onSubmit: 'false',
					url:'registrasi/upload/foto/'+kdsatker+'?_token=<?php echo $registrasi_token; ?>',
					theme:'registrasi',
					multi:'false',
					maxSize:0,
					showErrorAlerts:'false',
					showFilename: 'true',
					showPercent: 'true',
					onFileError:function(file,error){
					//validasi gagal upload
						alertify.log(error);
					},
					onFileSuccess:function(file,data){
						alertify.log('Upload file '+file.name+' berhasil');
					}
				});
			}
					
			//upload sk
			function upload_sk(kdsatker){	
				jQuery("#upload1").pekeUpload({
					//onSubmit: 'false',
					url:'registrasi/upload/sk/'+kdsatker+'?_token=<?php echo $registrasi_token; ?>',
					theme:'registrasi',
					multi:'false',
					maxSize:0,
					showErrorAlerts:'false',
					showFilename: 'true',
					showPercent: 'true',
					onFileError:function(file,error){
					//validasi gagal upload
						alertify.log(error);
					},
					onFileSuccess:function(file,data){
						alertify.log('Upload file '+file.name+' berhasil');
					}
				});
			}
					
			//upload sd
			function upload_sd(kdsatker){
				jQuery("#upload2").pekeUpload({
					//onSubmit: 'false',
					url:'registrasi/upload/sd/'+kdsatker+'?_token=<?php echo $registrasi_token; ?>',
					theme:'registrasi',
					multi:'false',
					maxSize:0,
					showErrorAlerts:'false',
					showFilename: 'true',
					showPercent: 'true',
					onFileError:function(file,error){
					//validasi gagal upload
						alertify.log(error);
					},
					onFileSuccess:function(file,data){
						alertify.log('Upload file '+file.name+' berhasil');
					}
				});
			}
					
			jQuery('#kdkppn').change(function(){
				var kdkppn=jQuery(this).val();
				jQuery.get('satker/'+kdkppn, function(result){
					if(result){
						jQuery('#kdsatker').html(result).trigger('chosen:updated');
						jQuery('#inp-kdkppn').val(kdkppn);
						jQuery('#form-ruh').show();
					}
					else{
						alertify.log('Data tidak dapat ditampilkan. Hubungi Administrator.');
					}
				});
			});
					
			jQuery('#kdsatker').change(function(){
				var kdsatker=jQuery(this).val();
				upload_sk(kdsatker);
				upload_sd(kdsatker);
				upload_foto(kdsatker);
			});
					
			jQuery('#kdnip').change(function(){
				var kdnip=jQuery(this).val();
				if(kdnip=='1'){
					jQuery('#div-nip').show();
					jQuery('#div-nip2').hide();
					jQuery('#div-upload-foto').show();
					jQuery('#div-upload-sk').show();
					jQuery('#div-upload-sd').hide();
					jQuery('#upload3').show();
					jQuery('#upload1').show();
					jQuery('#upload2').hide();
				}
				else if(kdnip=='2'){
					jQuery('#div-nip2').show();
					jQuery('#div-nip').show();
					jQuery('#div-upload-foto').show();
					jQuery('#div-upload-sk').show();
					jQuery('#div-upload-sd').hide();
					jQuery('#upload3').show();
					jQuery('#upload1').show();
					jQuery('#upload2').hide();
				}
				else{
					jQuery('#div-nip2').hide();
					jQuery('#div-nip').hide();
					jQuery('#div-upload-foto').show();
					jQuery('#div-upload-sk').show();
					jQuery('#div-upload-sd').show();
					jQuery('#upload3').show();
					jQuery('#upload1').show();
					jQuery('#upload2').show();
				}
			});
			
			var jQueryloading = jQuery('#loadingDiv').hide();
			jQuery(document)
			  .ajaxStart(function () {
				jQueryloading.show();
			  })
			  .ajaxStop(function () {
				jQueryloading.hide();
			  });
		});
	</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>