<?php namespace App\Http\Middleware;

use Closure;

class Authorization {
	
	public function handle($request, Closure $next)
    {
        $roles = array_except(func_get_args(), [0,1]);
		return $roles;
    }
	
}