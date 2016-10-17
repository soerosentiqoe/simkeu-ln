<?php namespace App\Http\Middleware;

use Closure;

class Oauth2_gmail {
	
	public function handle($request, Closure $next)
    {
        if(!session('access_token') || session('access_token')==null)
		{
			return redirect()->guest('google/mail/auth');
		}

		return $next($request);
    }
	
}