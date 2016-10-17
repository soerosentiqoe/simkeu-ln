<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			$app_code='b71efc81e3cee945aabd4e55c0376ad2';
			$url_portal='http://portal.djpbn.kemenkeu.go.id/login?app_code='.$app_code;
			$url_lokal='login';
			$key='26d46c91393216c5520057eb499bb2ce';
			
			//cek apakah ada sesi
			if(!session('authenticated')){
				
				//cek apakah ada portal token
				if(!isset($_COOKIE['modul_token_'.$app_code])){
					
					if ($request->ajax())
					{
						return '<script>window.location.href="'.$url_lokal.'";</script>';
					}
					else
					{
						return redirect()->guest($url_lokal);
						//return 'tidak ada token';
					}
					
				}
				else{
					
					try{
						
						$JWT = new \App\Libraries\jwtphp\JWT;
						$token=$_COOKIE['modul_token_'.$app_code];
						
						if($JWT->decode($token, $key, array('HS256'))){
							$json = $JWT->decode($token, $key);
							$data=json_decode($json,true);
							
							//cek sesi lokal
							if(session('authenticated')!==true){
								
								session([
									'authenticated' => true,
									'username' => $data['nama'],
									'kddept' => $data['kddept'],
									'kdunit' => $data['kdunit'],
									'kdsatker' => $data['kdsatker'],
									'kdkppn' => $data['kdkppn'],
									'kdkanwil' => $data['kdkanwil'],
									'kduappaw' => $data['kduappaw'],
									'id_user' => $data['id_user'],
									'kdlevel' => $data['kdlevel'],
									'nip' => $data['nip'],
									'tahun' => $data['tahun'],
									'thang' => substr($data['tahun'],2,2),
									'foto' => 'no-image.png'
								]);
								
							}
							
							return redirect()->guest($url_lokal);
							
						}
						else{
							return redirect()->guest($url_lokal);
							//return 'gagal dekripsi';
						}
						
					}
					catch(\Exception $e) {
						return redirect()->guest($url_lokal);
						//return $e;
					}
					
				}
			}
		}
		
		return $next($request);
	}

}
