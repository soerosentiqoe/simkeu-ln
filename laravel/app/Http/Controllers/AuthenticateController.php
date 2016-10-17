<?php namespace App\Http\Controllers;

use DB;
//use JWTAuth;
//use JWTFactory;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticateController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$rows = DB::table('t_site')->select('name_site,description,author,keyword,versi')->where(['aktif'=>'1'])->get();		
		$token = md5(uniqid(rand(), TRUE)).'.'.time();
		session(['login_token' => $token]);	
		session(['app_name'=>$rows[0]->name_site?$rows[0]->name_site:'DEFAULT APP']);	
		return view('login', array('app_versi'=>$rows[0]->versi, 'app_name'=>$rows[0]->name_site,'app_description'=>$rows[0]->description,'app_author'=>$rows[0]->author,'app_keyword'=>$rows[0]->keyword, 'token'=>$token));
	}
	
	public function login(Request $request)
	{
		//cek apakah ada token
		if(session('login_token')){
			
			$str_token = session('login_token');
			
			$arr_token = explode(".", $str_token);
			$token = $arr_token[0];
			$lifetime = $arr_token[1];
			
			//cek apakah token benar
			if($request->input('_token')==$str_token){
				
				$str_token_time = $lifetime;
				$token_time = (int)$str_token_time;
				$current_time = time();
				
				//cek apakah token kadaluwarsa
				if($token_time+300>=$current_time){ //5 menit
					
					$username = $request->input('username');
					$password = $request->input('password');
					$tahun = $request->input('tahun');		
					
					if($username!='' && $password!='' ){
					$rows=DB::table('T_ADMIN')
					    ->join('T_LEVEL','T_ADMIN.KDLEVEL','=','T_LEVEL.KDLEVEL')
					    ->select('T_ADMIN.NAMA','T_ADMIN.NIP','T_ADMIN.KDLEVEL','T_ADMIN.USERNAME','T_ADMIN.PASSWORD','T_ADMIN.ID','T_ADMIN.AKTIF','T_ADMIN.KDSATKER','T_LEVEL.NMLEVEL')
					    ->where('T_ADMIN.USERNAME','=',$username)
					    ->get();
						
						if(isset($rows[0]) && $rows[0]->password){
						
							if($rows[0]->password==md5($password)){
							
								if($rows[0]->aktif=='1'){
								
								session([
									'authenticated' => true,
									'nama'=>$rows[0]->nama,
									'nip'=>$rows[0]->nip,
									'kdlevel'=>$rows[0]->kdlevel,
									'id'=>$rows[0]->id,
									'kdsatker'=>$rows[0]->kdsatker,
									'nmlevel'=>$rows[0]->nmlevel,
									'thang'=>$request->input('tahun'),
									'kdbpp'=>'000'
											]);

									return response()->json(['error' => false,'message' => 'Login berhasil!</br>Selamat Datang']);
									
								}
								else{
									return response()->json(['error' => true,'message' => 'User tidak aktif!']);
								}
								
							}
							else{
								return response()->json(['error' => true,'message' => 'Password salah!']);
							}
						
						}
						else{
							return response()->json(['error' => true,'message' => 'Username tidak terdaftar!']);
						}
						
					}
					else{
						return response()->json(['error' => true,'message' => 'Parameter tidak valid!']);
					}
					
					
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token kadaluarsa)')), 403);
				}
				
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token tidak valid)')), 403);
			}
		
		}
		else{
			return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token)')), 403);
		}
	}
		
	public function logout()
	{
		// unset cookies
		if (isset($_SERVER['HTTP_COOKIE'])) {
			$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
			foreach($cookies as $cookie) {
				$parts = explode('=', $cookie);
				$name = trim($parts[0]);
				setcookie($name, '');
			}
		}
		
		setcookie('espm_session', null);
		setcookie('XSRF-TOKEN', null);
		
		unset($_COOKIE['espm_session']);
		unset($_COOKIE['XSRF-TOKEN']);
		
		Session::flush();
		
		return redirect()->guest('/');
	}
	
	public function token()
	{
		return csrf_token();
	}
	
	public function level()
	{
		if(session('kdlevel')=='00'){
			return response()->json(['level' => 'success']);
		}
	}
	
	public function level_by_kode($kdlevel)
	{
		if(session('kdlevel')==$kdlevel){
			return response()->json(['level' => 'success']);
		}
	}
	
	
	
	public function destroy_token()
	{
		
	}
	
	public function registrasi()
	{
		$rows = DB::table('t_site')->select('name,description,keyword,versi')->get();		
		$data['registrasi_token']=md5(uniqid(rand(), TRUE)).'.'.time();
		$data['app_versi']=$rows[0]->versi;
		$data['app_nama']=$rows[0]->nama;
		session(['registrasi_token' => $data['registrasi_token']]);
		session(['upload_registrasi_foto'=>null]);
		session(['upload_registrasi_sk'=>null]);
		session(['upload_registrasi_sd'=>null]);
		return view('registrasi', $data);
	}
	
	public function upload_sk(Request $request, $kdsatker)
	{
		//cek apakah ada token
		if(session('registrasi_token')){
			
			$str_token = session('registrasi_token');
			
			$arr_token = explode(".", $str_token);
			$token = $arr_token[0];
			$lifetime = $arr_token[1];
			
			//cek apakah token benar
			if($request->input('_token')==$str_token){
				
				$str_token_time = $lifetime;
				$token_time = (int)$str_token_time;
				$current_time = time();
				
				//cek apakah token kadaluwarsa
				if($token_time+600>=$current_time){ //10 menit
					
					$targetFolder = 'data/user/sk/'; // Relative to the root
		
					if (!empty($_FILES)) {
						$file_name = $_FILES['file']['name'];
						$tempFile = $_FILES['file']['tmp_name'];
						$targetFile = $targetFolder.$file_name;
						$fileTypes = array('PDF','pdf'); // File extensions
						$fileParts = pathinfo($_FILES['file']['name']);
						$fileSize = $_FILES['file']['size'];
						
						//type file sesuai..??	
						if (in_array($fileParts['extension'],$fileTypes)) {
							
							//isi kosong..??
							if($fileSize>0 && $fileSize<=1000000){
								
								$file_name_baru= 'sk_'.$kdsatker.'_'.md5(uniqid(rand(), TRUE)).'.pdf';
										
								move_uploaded_file($tempFile,$targetFolder.$file_name_baru);
								
								//cek apakah berhasil diupload
								if(file_exists($targetFolder.$file_name_baru)){
									
									session(['upload_registrasi_sk'=>$file_name_baru]);
									
									return '1';
									
								}
								else{
									echo 'File gagal diupload.';
								}
				
							}
							else{
								echo 'Ukuran file tidak valid, periksa data anda.';
							}
						}
						else{
							echo 'Tipe file tidak sesuai.';
						}
					}
					else{
						echo 'Tidak ada file yang diupload.';
					}
					
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token kadaluarsa)')), 403);
				}
				
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token tidak valid)')), 403);
			}
		
		}
		else{
			return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token)')), 403);
		}
			
	}
	
	public function upload_sd(Request $request, $kdsatker)
	{
		//cek apakah ada token
		if(session('registrasi_token')){
			
			$str_token = session('registrasi_token');
			
			$arr_token = explode(".", $str_token);
			$token = $arr_token[0];
			$lifetime = $arr_token[1];
			
			//cek apakah token benar
			if($request->input('_token')==$str_token){
				
				$str_token_time = $lifetime;
				$token_time = (int)$str_token_time;
				$current_time = time();
				
				//cek apakah token kadaluwarsa
				if($token_time+600>=$current_time){ //10 menit
					
					$targetFolder = 'data/user/sd/'; // Relative to the root
		
					if (!empty($_FILES)) {
						$file_name = $_FILES['file']['name'];
						$tempFile = $_FILES['file']['tmp_name'];
						$targetFile = $targetFolder.$file_name;
						$fileTypes = array('PDF','pdf'); // File extensions
						$fileParts = pathinfo($_FILES['file']['name']);
						$fileSize = $_FILES['file']['size'];
						
						//type file sesuai..??	
						if (in_array($fileParts['extension'],$fileTypes)) {
							
							//isi kosong..??
							if($fileSize>0 && $fileSize<=1000000){
								
								$file_name_baru= 'sd_'.$kdsatker.'_'.md5(uniqid(rand(), TRUE)).'.pdf';
										
								move_uploaded_file($tempFile,$targetFolder.$file_name_baru);
								
								//cek apakah berhasil diupload
								if(file_exists($targetFolder.$file_name_baru)){
									
									session(['upload_registrasi_sd'=>$file_name_baru]);
									
									return '1';
									
								}
								else{
									echo 'File gagal diupload.';
								}
				
							}
							else{
								echo 'Ukuran file tidak valid, periksa data anda.';
							}
						}
						else{
							echo 'Tipe file tidak sesuai.';
						}
					}
					else{
						echo 'Tidak ada file yang diupload.';
					}
					
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token kadaluarsa)')), 403);
				}
				
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token tidak valid)')), 403);
			}
		
		}
		else{
			return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token)')), 403);
		}
			
	}
	
	public function upload_foto(Request $request, $kdsatker)
	{
		//cek apakah ada token
		if(session('registrasi_token')){
			
			$str_token = session('registrasi_token');
			
			$arr_token = explode(".", $str_token);
			$token = $arr_token[0];
			$lifetime = $arr_token[1];
			
			//cek apakah token benar
			if($request->input('_token')==$str_token){
				
				$str_token_time = $lifetime;
				$token_time = (int)$str_token_time;
				$current_time = time();
				
				//cek apakah token kadaluwarsa
				if($token_time+600>=$current_time){ //10 menit
					
					$targetFolder = 'data/user/'; // Relative to the root
		
					if (!empty($_FILES)) {
						$file_name = $_FILES['file']['name'];
						$tempFile = $_FILES['file']['tmp_name'];
						$targetFile = $targetFolder.$file_name;
						$fileTypes = array('PNG','png'); // File extensions
						$fileParts = pathinfo($_FILES['file']['name']);
						$fileSize = $_FILES['file']['size'];
						
						//type file sesuai..??	
						if (in_array($fileParts['extension'],$fileTypes)) {
							
							//isi kosong..??
							if($fileSize>0 && $fileSize<=1000000){
								
								$file_name_baru= $kdsatker.'_'.md5(uniqid(rand(), TRUE)).'.png';
										
								move_uploaded_file($tempFile,$targetFolder.$file_name_baru);
								
								//cek apakah berhasil diupload
								if(file_exists($targetFolder.$file_name_baru)){
									
									session(['upload_registrasi_foto'=>$file_name_baru]);
									
									return '1';
									
								}
								else{
									echo 'File gagal diupload.';
								}
				
							}
							else{
								echo 'Ukuran file tidak valid, periksa data anda.';
							}
						}
						else{
							echo 'Tipe file tidak sesuai.';
						}
					}
					else{
						echo 'Tidak ada file yang diupload.';
					}
					
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token kadaluarsa)')), 403);
				}
				
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token tidak valid)')), 403);
			}
		
		}
		else{
			return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token)')), 403);
		}
		
	}
	
	public function registrasi_tambah(Request $request)
	{
		//cek apakah ada token
		if(session('registrasi_token')){
			
			$str_token = session('registrasi_token');
			
			$arr_token = explode(".", $str_token);
			$token = $arr_token[0];
			$lifetime = $arr_token[1];
			
			//cek apakah token benar
			if($request->input('_token')==$str_token){
				
				$str_token_time = $lifetime;
				$token_time = (int)$str_token_time;
				$current_time = time();
				
				//cek apakah token kadaluwarsa
				if($token_time+600>=$current_time){ //10 menit
					
					$lanjut=true;
					$kdnip=$request->input('kdnip');
					$nip=$request->input('nip');
					$nip2=$request->input('nip2');
					
					if($kdnip=='1'){ //nip harus = 18
						if($nip=='' || strlen($nip)!=18){
							$lanjut=false;
						}
					}
					elseif($kdnip=='2'){
						if($nip=='' || $nip2==''){
							$lanjut=false;
						}
						else{
							$nip=str_pad($nip,16,"0",STR_PAD_RIGHT).str_pad((string)strlen($nip),2,"0",STR_PAD_LEFT);
						}
					}
					
					if($lanjut==true){
						
						if(session('upload_registrasi_sk')!='' && session('upload_registrasi_sk')!=null){
							
							$lanjut=true;
							if($kdnip=='3'){
								if(session('upload_registrasi_sd')=='' || session('upload_registrasi_sd')==null){
									$lanjut=false;
								}
							}
							
							if($lanjut==true){
								
								if(session('upload_registrasi_foto')!='' && session('upload_registrasi_foto')!=null){
									
									//cek level
									if($request->input('kdlevel')=='05' || $request->input('kdlevel')=='06'){
										
										//cek panjang nip
										//if(strlen($request->input('nip'))==18){
											
											//cek email
											if(filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)){ 
												
												//cek panjang username dan passwordnya
												if(strlen($request->input('username'))>=6 && strlen($request->input('password'))>=8){
													
													//cek password
													if($request->input('password')==$request->input('password1')){
														
														//cek format username
														if(preg_match('/^[a-zA-Z0-9_]+$/',$request->input('username'))){
															
															//cek duplikasi username
															$rows = DB::select("
																select count(*) as jml from t_user where username=?
															",
																[$request->input('username')]
															);
															
															if($rows[0]->jml==0){
																
																$password=md5($request->input('password'));
																$insert = DB::insert("
																insert into t_user
																	(id, username, password, nama, kdnip, nip, nip2, nodipa, tgdipa, telp, email, jabatan, alamat, kdlevel, kdkppn, kdkanwil, kdsatker, foto, aktif, nmfilesk, nmfilesd) 
																	values (seq_id_user.nextval, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '0', ?, ?)",
																	[
																		$request->input('username'),
																		$password,
																		$request->input('nama'),
																		$request->input('kdnip'),
																		$nip,
																		$request->input('nip2'),
																		$request->input('nodipa'),
																		$request->input('tgdipa'),
																		$request->input('telp'),
																		$request->input('email'),
																		$request->input('jabatan'),
																		$request->input('alamat'),
																		$request->input('kdlevel'),
																		$request->input('inp-kdkppn'),
																		'',
																		$request->input('kdsatker'),
																		session('upload_registrasi_foto'),
																		session('upload_registrasi_sk'),
																		session('upload_registrasi_sd')
																	]
																);
																
																if($insert==true) {													
																	return 'success';
																} else {
																	return 'Proses simpan gagal. Hubungi Administrator.';
																}
																
															}
															else{
																return 'Username ini telah terdaftar. Silahkan gunakan username yang lain.';
															}
															
														}
														else{
															return 'Format username tidak sesuai!';
														}
														
													}
													else{
														return 'Password tidak sesuai!';
													}
													
												}
												else{
													return 'Panjang format username minimal 6 karakter dan password minimal 8 karakter!';
												}
												
											}
											else{
												return 'Format email tidak sesuai. Data tidak dapat disimpan.';
											}
											
										/*}
										else{
											return 'NIP/NRP harus diisi 18 digit angka. Tambahkan angka 0 dibelakang khusus untuk NRP dan NIP 9 digit.';
										}*/
										
									}
									else{
										return 'Level user Anda tidak sesuai!';
									}
									
								}
								else{
									return 'Anda belum mengupload foto user!';
								}
								
							}
							else{
								return 'Anda belum mengupload Surat Dispensasi!';
							}
							
						}
						else{
							return 'Anda belum mengupload SK Penunjukan Pengantar SPM!';
						}
						
					}
					else{
						return 'NIP/NRP harus diisi 18 digit angka!';
					}
					
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token kadaluarsa)')), 403);
				}
				
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token tidak valid)')), 403);
			}
		
		}
		else{
			return response(json_encode(array('error' => true,'message' => 'Anda tidak memiliki akses! (Token)')), 403);
		}
		
	}
	
	public function cekUsername(Request $request)
	{
		if (isSet($_GET['username']))
		{                                                               
			$rows = DB::select("
				select count(*) as jml from t_user where username=?
				",
				[$_GET['username']]
				);
			
			if($rows[0]->jml==0){
				return 'true';
			}
			else{
				return 'false';
			}
		}
	}

}
