<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* authenticate routes */
Route::get('/login', 'AuthenticateController@index');
Route::post('/auth', 'AuthenticateController@login');
Route::get('/logout', 'AuthenticateController@logout');

/* group app routes (session only) */
Route::group(['middleware' => 'auth'], function(){
	
	/* Get App for angular */
	Route::get('/', 'AppController@index');
	Route::get('/ref/kanwil', 'ReferensiController@kanwil');
	
	Route::get('/', 'AppController@index');
	Route::get('/ref/satker', 'RefSatkerController@satker');

	Route::get('/coba', 'TestController@test');
		
	//route token
	Route::get('/token', 'AuthenticateController@token');
	
	//route cek level
	Route::get('/cek/level', 'AuthenticateController@level');
	Route::get('/cek/level/{kdlevel}', 'AuthenticateController@level_by_kode');
	
	//route utama	
	Route::get('/profile', 'ProfileController@index');
	Route::put('/profile', 'ProfileController@ubah');
	Route::post('/profile', 'ProfileController@upload');
	
	/* Route for development */
	/* Route utama */
	//route upload SPM
	Route::post('/upload/spm', 'SPMController@upload');
	//route upload ADK Silabi
	Route::post('/Upload/AdkSilabi', 'AdkSilabiController@upload');
	Route::post('/Upload/AdkRPDHarian', 'AdkRPDController@upload');
	
	//Antrian
	Route::get('/antrian/alokasi-kppn', 'AntrianController@index');
	Route::get('/antrian/alokasi-kppn/{id}', 'AntrianController@pilih');
	Route::get('/antrian/test', 'AntrianController@test');
	Route::post('/antrian/alokasi-kppn', 'AntrianController@tambah');
	Route::post('/antrian/alokasi-kppn-ubah', 'AntrianController@ubah');
	Route::delete('/antrian/alokasi-kppn', 'AntrianController@hapus');
	Route::get('/antrian/alokasi-lama/{jnsspm}/{tgl_antrian}', 'AntrianController@alokasi_lama');
	Route::get('/antrian/alokasi/{tgl_antrian}', 'AntrianController@alokasi');
	Route::get('/antrian/loket/{tgl_antrian}', 'AntrianController@loket');
	Route::get('/antrian/tanggal', 'AntrianController@tanggal');
	Route::post('/antrian/upload', 'AntrianController@upload');
	Route::post('/antrian/proses', 'AntrianController@proses');
	
	//route monitoring SPM
	Route::get('/monitoring/antrian-spm', 'AntrianController@monitoring');
	Route::get('/monitoring/antrian-spm/tanggal', 'AntrianController@tanggal');
	Route::get('/monitoring/antrian-spm/loket/{tgl_antrian}', 'AntrianController@loket');
	Route::get('/monitoring/antrian-spm/data/{tgl_antrian}/{kdloket}', 'AntrianController@monitoring_by_loket');
	Route::get('/monitoring/spm', 'SPMController@monitoring');
/* <<<<<<< HEAD */
	Route::get('/ref/unit', 'UnitController@tampil');	
/* ======= */
	Route::get('/transaksi/silabi', 'AdkSilabiController@index');
	// route monitoring adk silabi
	Route::get('/monitoring/silabi', 'AdkSilabiController@monitoring');
	/* Route referensi */
	//route 
	//Route::get('/ref/satker', 'RefSatkerController@index');
	Route::get('/ref/satker/{kdsatker}', 'SatkerController@cari_by_satker');
	Route::get('/ref/satker-kppn/{kppn}', 'SatkerController@cari_by_kppn');
	Route::post('/ref/satker', 'SatkerController@tambah');
	Route::put('/ref/satker', 'SatkerController@ubah');
	Route::delete('/ref/satker', 'SatkerController@hapus');

	// route KPPN
	//Route::get('/ref/kppn', 'RefKPPNController@referensi');

	// route DEPT
	Route::get('/ref/dept', 'DeptControllerCrud@tayang');
	Route::get('/ref/cari/{id}', 'DeptControllerCrud@cari');
	Route::post('/ref/dept', 'DeptControllerCrud@tambah');
	Route::put('/ref/dept', 'DeptControllerCrud@ubah');
	Route::delete('/ref/dept/{id}', 'DeptControllerCrud@hapus');

	// route RUH KPPN
	Route::get('/ref/ruhkppn', 'RuhKPPNController@index');
	Route::get('/ref/carikppn/{id}', 'RuhKPPNController@cari');
	Route::post('/ref/ruhkppn', 'RuhKPPNController@tambah');
	Route::put('/ref/ruhkppn', 'RuhKPPNController@ubah');
	Route::delete('/ref/ruhkppn', 'RuhKPPNController@hapus');
	
	//referensi user
	Route::get('/ref/user', 'RefUserController@index');
	Route::get('/ref/user/{id}', 'RefUserController@cari');
	Route::post('/ref/user', 'RefUserController@tambah');
	Route::put('/ref/user', 'RefUserController@ubah');
	Route::put('/ref/user/aktif', 'RefUserController@aktif');
	Route::put('/ref/user/non-aktif', 'RefUserController@nonaktif');
	Route::put('/ref/user/reset', 'RefUserController@reset');
	Route::delete('/ref/user', 'RefUserController@hapus');
	
	//route untuk laporan rekap lpj kppn
	Route::post('/lpjkppn/rekap', 'CRLKPPNController@rekap');
	Route::get('/lpjkppn/rekap', 'CRLKPPNController@index');
	
	//route untuk laporan rekap lpj kanwil
	Route::post('/lpjkanwil/rekap', 'CRLKanwilController@rekap');
	Route::get('/lpjkanwil/rekap', 'CRLKanwilController@index');
	
	//route untuk laporan rekap lpj pkn
	Route::post('/lpjpkn/rekap', 'CRLPKNController@rekap');
	Route::get('/lpjpkn/rekap', 'CRLPKNController@index');
	
	//route download kode booking
	Route::get('/download/kode-booking/{id}', 'DownloadController@kode_booking');
	
});

	// route Monitoring Renkas
	Route::get('/monitoring/renkas', 'MonrenkasController@monitoring');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

//API Route
Route::group(['prefix' => 'api'], function () {
	
	//Versi 1
	Route::group(['prefix' => 'v1', 'middleware' => 'token'], function () {
		
		//Group SPM
		Route::group(['prefix' => 'spm'], function () {
			
			//Group Data SPM
			Route::group(['prefix' => 'data'], function () {
				
				Route::get('', 'ApiSPMController@data'); //get all spm
				Route::get('/{id}', 'ApiSPMController@data_by_id'); //get spm by id
				Route::put('/{id}/proses', 'ApiSPMController@update_proses_by_id'); //update status spm by id
				Route::delete('/{id}/proses', 'ApiSPMController@update_batal_by_id'); //delete status spm by id
				
			});
			
			//Group ADK SPM
			Route::group(['prefix' => 'adk'], function () {
				
				Route::get('/{id}', 'ApiSPMController@adk_by_id'); //get spm by id
				
			});
			
		});
        // Group ADK SILABI
        	Route::group(['prefix' => 'silabi'], function () {
			
			//Group Data Silabi
			Route::group(['prefix' => 'data'], function () {
				
				Route::get('/{kdkppn}/{bulan}/{tahun}', 'ApiSilabiController@data'); //get all data silabi group by kdkppn
				Route::get('/{kdkppn}/{id}', 'ApiSilabiController@data_by_id'); //get data silabi group by kdsatker
				Route::put('/{kdkppn}/{id}/proses', 'ApiSilabiController@update_proses_by_id'); //update data silabi group by kdsatker
				Route::delete('/{kdkppn}/{id}/proses', 'ApiSilabiController@update_batal_by_id'); //delete status spm by id
				
			});
			
			//Group ADK Silabi
			Route::group(['prefix' => 'adk'], function () {
				
				Route::get('/{id}', 'ApiSilabiController@adk_by_id'); //get spm by id
				
			});
			
		});
		
	});
	
});
