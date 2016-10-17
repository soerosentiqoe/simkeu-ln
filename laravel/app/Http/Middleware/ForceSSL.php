<?php namespace App\Http\Middleware;

use Closure;

class ForceSSL {

    public function handle($request, Closure $next)
    {
        if (empty($_SERVER['HTTPS'])) {
			return response(json_encode(array('error' => true,'message' => 'SSL Required!')), 403.4);
			exit;
		}

		return $next($request); 
		
    }
	
}