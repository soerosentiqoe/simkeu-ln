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
	// route File
	Route::post('/upload/file', 'FileController@upload');	
	//route DIPA	
    Route::post('/dipa/process', 'DipaController@process');
    // Route::post('/dipa/simpan', 'DipaController@simpanDipa');
    Route::get('/dipa/monitoring', 'DipaController@monitoring');
    Route::get('/dipa/pagurealisasi', 'DipaController@getPaguRealisasi');
    Route::get('/dipa/pagurealisasiperoutput', 'DipaController@getPaguRealisasiPerOutput');
    Route::get('/dipa/detailakun','DipaController@getDetailKdakun');
    // SPP
    Route::get('/spp/monitoring','SppController@monitoring');
    
    //SPM
     Route::post('/process/spm', 'SpmController@process');
    Route::get('/spm/getmaxnilkurs','SpmController@getMaxNilkurs');
    Route::get('/spm/monitoring','SpmController@monitoring');
    
    //dropdown
    Route::get('/dropdown/getjenbuku', 'DropdownController@getJenbuku');
    Route::get('/dropdown/getvalas', 'DropdownController@getValas');
     Route::get('/dropdown/getreftran', 'DropdownController@getRefTran');
     Route::get('/dropdown/getkdjenspm', 'DropdownController@getKdjenspm');
     Route::get('/dropdown/getkdprogram', 'DropdownController@getKdprogram');
    Route::get('/dropdown/getkdgiat', 'DropdownController@getKdgiat');
    Route::get('/dropdown/getkdoutput', 'DropdownController@getKdoutput');
    Route::get('/dropdown/getkdsoutput', 'DropdownController@getKdsoutput');
    Route::get('/dropdown/getkdkmpnen', 'DropdownController@getKdkmpnen');
    Route::get('/dropdown/getkdskmpnen', 'DropdownController@getKdskmpnen');
    Route::get('/dropdown/getkdakun', 'DropdownController@getKdakun');
    Route::get('/dropdown/getbulan', 'DropdownController@getBulan');
    Route::get('/dropdown/getkdlevel', 'DropdownController@getKdlevel');
    Route::get('/dropdown/getkdsatker', 'DropdownController@getKdsatker');
    Route::get('/dropdown/getgroupmenu', 'DropdownController@getGroupMenu');
    Route::get('/dropdown/getmenu', 'DropdownController@getMenu');
    Route::get('/dropdown/getjenissppgu', 'DropdownController@getJenisSppGu');
    Route::get('/dropdown/getdistinctkdvalasbuku', 'BukuController@dropdownDistinctKdvalas');
    Route::get('/dropdown/getpejabat','DropdownController@getPejabat');
    
    
    //routes saldoawal
    Route::post('/saldoawal/add', 'SaldoawalController@add');
    Route::put('/saldoawal/add', 'SaldoawalController@ubah');
    Route::delete('/saldoawal/add', 'SaldoawalController@hapus');
    Route::get('/saldoawal/monitoring', 'SaldoawalController@monitoring');
    Route::get('/saldoawal/cari/{id}', 'SaldoawalController@getDataById');
    
    // transaksi
    Route::get('/transaksi/referensi/{id}', 'TransaksiController@getRefById');
    Route::get('/transaksi/loadspm', 'SpmController@loadSpm');
    Route::get('/transaksi/getmaxnotran', 'TransaksiController@getMaxNotran');
    Route::post('/transaksi/add', 'TransaksiController@add');
    Route::delete('/transaksi/add', 'TransaksiController@hapus');
    Route::get('/transaksi/monitoring', 'TransaksiController@monitoring');
    //SPBY
    Route::post('/spby/add', 'SpbyController@add');
    Route::get('/spby/monitoring', 'SpbyController@monitoring');
    Route::get('/spby/getmaxno', 'SpbyController@getMaxNo');
    Route::get('/spby/cari/{id}', 'SpbyController@getDataById');
    Route::put('/spby/add', 'SpbyController@ubah');
    Route::delete('/spby/add', 'SpbyController@hapus');
	//DRPP
    Route::post('/drpp/add', 'DrppController@add');
    Route::get('/drpp/monitoring', 'DrppController@monitoring');
    Route::get('/drpp/getmaxno', 'DrppController@getMaxNo');
    Route::get('/drpp/cari/{id}', 'DrppController@getDataById');
    Route::put('/drpp/add', 'DrppController@ubah');
    Route::delete('/drpp/add', 'DrppController@hapus');
    Route::get('/drpp/transaksi', 'DrppController@getTransaksi');
    //kuitansi
    Route::get('/kuitansi/getmaxno', 'KuitansiController@getMaxNokwt');
    Route::get('/kuitansi/monitoring', 'KuitansiController@monitoring');
    Route::post('/kuitansi/add', 'KuitansiController@add');
    Route::get('/kuitansi/getkuitansigu','KuitansiController@getKuitansiGu');
	
    // buku
    Route::get('/buku/monitoring', 'BukuController@monitoring');
    Route::get('/buku/hitungbukulpj', 'BukuController@hitungBukuLpj');
    Route::get('/buku/maxnobuku', 'BukuController@maxNoBuku');
    // BA Pengeluaran
    Route::post('/bak/add', 'BakController@add');
    Route::get('/bak/monitoring', 'BakController@monitoring');
    Route::get('/bak/cari/{id}', 'BakController@getDataById');
    Route::put('/bak/add', 'BakController@ubah');
    Route::delete('/bak/add', 'BakController@hapus');
    
    //Referensi
    //User
    Route::post('/user/add', 'UserController@add');
    Route::get('/user/monitoring', 'UserController@monitoring');
    Route::get('/user/cari/{id}', 'UserController@getDataById');
    Route::put('/user/add', 'UserController@ubah');
    Route::delete('/user/add', 'UserController@hapus');
    // rule group menu
    Route::get('/rulemenu/monitoring', 'RuleGroupMenuController@monitoring');
    Route::post('/rulemenu/add', 'RuleGroupMenuController@add');   
    Route::get('/rulemenu/cari/{id}', 'RuleGroupMenuController@getDataById');   
    Route::delete('/rulemenu/add', 'RuleGroupMenuController@hapus');
    // pejabat
    Route::post('/pejabat/add', 'PejabatController@add');
    Route::get('/pejabat/monitoring', 'PejabatController@monitoring');
    Route::get('/pejabat/cari/{id}', 'PejabatController@getDataById');
    Route::put('/pejabat/add', 'PejabatController@ubah');
    Route::delete('/pejabat/add', 'PejabatController@hapus');
    
	


});
?>
