<!DOCTYPE html>
<html>
<head>
	<title> -SPM - Cetakan </title>
	<link href="{{ asset('/template/img/favicontil.ico')}}" rel="icon" type="image/x-icon" />
	<link href="{{ asset('/template/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('/template/font-awesome-4.2.0/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />	
</head>
<style>
	th{
		text-align:center;
	}
</style>
<body>
	
	<center>
		<h2>Kementerian Keuangan RI</h2>
		<h3>Direktorat Jenderal Perbendaharaan</h3>
		<hr>
		<h3>Antrian Elektronik SPM Instansi</h3>
	</center>
	<br>
	<center>
		<h3>
			Kode Booking <?php echo $kode_booking; ?><br>
			KPPN <?php echo $kdkppn; ?><br>
			Loket <?php echo $kdloket; ?>
		</h3>
		<br>
		<br>
		<div class="container">
			<div class="col-lg-3"></div>
			<div class="col-lg-6">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>No</th>
							<th>Waktu</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $tabel_waktu; ?>
					</tbody>
				</table>
			</div>
			<div class="col-lg-3"></div>
		</div>
	</center>
	
</body>
</html>