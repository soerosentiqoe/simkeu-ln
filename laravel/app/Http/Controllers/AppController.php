<?php namespace App\Http\Controllers;

use DB;

class AppController extends Controller {

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
		//$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		// name_site
		$data_site = DB::table('t_site')->select('name_site,description,author,keyword,versi')->where(['aktif'=>'1'])->get();	
		// Create menu....
		$menus =DB::table('group_menu')->where(['kdlevel'=>session('kdlevel')])->orderBy('urut','asc')->get();		
		$html_out='';
		$angular = 'var app = angular.module("spa", ["ui.router","chieffancypants.loadingBar"]);
					app.config(function($stateProvider, $urlRouterProvider){
					$urlRouterProvider.otherwise("/");
					$stateProvider';
		
		foreach($menus as $menu) {
		
			if($menu->parent=='0'){
				//jika tidak, tidak perlu buat sub menu
				
				//apakah buka tab baru?
				if($menu->new_tab=='1'){
					$html_out .= '<li>	
									<a href="'.$menu->url.'" target="_blank">
										<i class="'.$menu->icon.'"></i> '.$menu->nama_group_menu.'
									</a>
								</li>';
				}
				else{					
					if($menu->url==''){
						$html_out .= '<li>	
										<a ui-sref="/">
											<i class="'.$menu->icon.'"></i> '.$menu->nama_group_menu.'
										</a>
									</li>';
						$angular .= '.state("/", {
										url: "/",
										templateUrl: "partials/'.$menu->nmfile.'"
									})';
					}
					else{
						$html_out .= '<li>	
										<a ui-sref="'.$menu->url.'">
											<i class="'.$menu->icon.'"></i> '.$menu->nama_group_menu.'
										</a>
									</li>';
						$angular .= '.state("'.$menu->url.'", {
										url: "/'.$menu->url.'",
										templateUrl: "partials/'.$menu->nmfile.'"
									})';
					}
				}
				
			}
			else{
				//jika ya, perlu buat sub menu dengan parameter parent_id ybs
				$html_out .= '<li class="treeview">
								<a href="javascript:;">
									<i class="'.$menu->icon.'"></i>
									<span>'.$menu->nama_group_menu.'</span>
									<i class="fa fa-angle-left pull-right"></i>
								</a>
								<ul class="treeview-menu">';
				
				
				$sub_menus=DB::table('t_menu')->where(['id_group_menu'=>$menu->id])->orderBy('urut','asc')->get();
				
				//bentuk sub menu
				foreach($sub_menus as $sub_menu){
					
					//apakah tab baru?
					if($sub_menu->new_tab=='1'){
						$html_out .= '<li><a href="'.$sub_menu->url.'" target="_blank"><i class="fa fa-angle-double-right"></i> '.$sub_menu->nmmenu.'</a></li>';
					}
					else{
						$html_out .= '<li><a ui-sref="'.$sub_menu->url.'"><i class="fa fa-angle-double-right"></i> '.$sub_menu->nmmenu.'</a></li>';
						$angular .= '.state("'.$sub_menu->url.'", {
										url: "/'.$sub_menu->url.'",
										templateUrl: "partials/'.$sub_menu->nmfile.'"
									})';
					}
					
				}
				
				$html_out .= 	'</ul>
							</li>';
			}
			
		}
        $angular .= '.state("/", {
										url: "/",
										templateUrl: "partials/Dashboard.html"
									})';
		/*
		$angular .= '.state("profile", {
							url: "/profile",
							templateUrl: "partials/profile.html"
						});
					});';
		*/
		$angular .= '});';
		
		
		
		return view('app',
			[
				'menu' => $html_out,
				'angular' => $angular,
				'info_nmkantor' => session('nama'),
				'info_nmlevel' => session('nmlevel'),
				'info_foto' => session('nama'),
				'info_username' => session('username'),
				'info_nama' => session('nama'),
				'info_email' => session('nip'),
				'info_tahun' => session('thang'),
                'app_name'=>$data_site[0]->name_site,
			    'app_description'=>$data_site[0]->description,
			    'app_author'=>$data_site[0]->author,
			    'app_keyword'=>$data_site[0]->keyword,
				'app_versi'=>$data_site[0]->versi
			]
		);
	}

}
