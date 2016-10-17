<?php namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class Google {

    protected $client;

    protected $service;

    function __construct()
	{
		$client = new \Google_Client();
		$client->setAuthConfigFile('c:\credentials\client_secret.json');
		$client->setAccessType('offline');
		
		$this->service = $client;
    }
	
	public function getClient()
	{
		$gmail = $this->service;
		return $gmail;
	}
	
}

?>