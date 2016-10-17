<?php namespace App\Http\Middleware;

use Closure;
use DB;

class CheckToken {

    /*public function handle($request, Closure $next)
    {
        $JWT = new \App\Libraries\jwtphp\JWT;
		$key = 'cinta123!';
		$headers = apache_request_headers();
		
		if(isset($headers['Authorization'])){
			$token = str_replace(" ","", str_replace("Bearer ", "", $headers['Authorization']));
			if($JWT->decode($token, $key)){
				$json = $JWT->decode($token, $key);
				$data=json_decode($json,true);
				$request->merge(array("kdbank" => $data['iss'], "kdlevel" => $data['kdlevel']));
				return $next($request, $json);
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Token tidak valid!')), 403);
			}
		}
		else{
			if(isset($_GET['token'])){
				$token = $_GET['token'];
				if($JWT->decode($token, $key)){
					$json = json_decode($JWT->decode($token, $key));
					$id_user = $json->iss;
					$kdlevel = $json->kdlevel;
					return $next($request, $json);
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Token tidak valid!')), 403);
				}
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Token tidak ada!')), 401);
			}
		}
    }*/
	
	//token untuk aplikasi sas
	public function handle($request, Closure $next)
    {
        if(isset($_GET['token']) && isset($_GET['kdkppn'])){
			$token = $_GET['token'];
			$kdkppn = $_GET['kdkppn'];
			
			$rows = DB::select("
				select api_key
				from t_api_key
				where kdkppn=?
			",[
				$kdkppn
			]);
			
			//kode kppn ditemukan
			if(isset($rows[0])){
				
				$token_=$rows[0]->api_key;
				
				//apakah token sesuai
				if($token==$token_){
					
					//teruskan dengan membawa kode kppn
					return $next($request, ['kdkppn'=>$kdkppn]);
					
				}
				else{
					return response(json_encode(array('error' => true,'message' => 'Token tidak sesuai!')), 403);
				}
				
			}
			else{
				return response(json_encode(array('error' => true,'message' => 'Kode KPPN atau token tidak terdaftar!')), 403);
			}
			
		}
		else{
			return response(json_encode(array('error' => true,'message' => 'Token atau kode KPPN tidak ada!')), 401);
		}
    }
	
}