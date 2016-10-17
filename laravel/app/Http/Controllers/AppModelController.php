<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ZipArchive;

class AppModelController extends Controller {

	
	public function __construct()
	{
		
	}

	
	public function index()
	{
		
	}
     public function saveData($data,$table) {
        DB::beginTransaction();
		DB::table($table)->insert($data);
        DB::commit();    
        
    }
    public function getMaxId($table,$column) {
        return DB::table($table)->max($column);
    }
    public function getById($id,$table){
        return DB::table($table)
		->where (array('ID'=>$id ))
		->get();
    }
    public function getFirstRowByWhere($table,$where,$column) {
        $rows= DB::table($table)
            ->where ($where)
            ->select($column)
            ->get();
//print_r($rows);
        $hasil=0;
        if (count($rows) > 0) {
                foreach($rows as $row) {
                $hasil=$row->$column;
            }
        }
        
        return $hasil;
    }
    public function getCountByWhere($table,$where) {
        $rows=DB::table($table)->where($where)->get();
        return count($rows);
        
    }
    
    public function updateData($table,$data,$where) {
        DB::beginTransaction();
		DB::table($table)->where($where)->update($data);
        DB::commit(); 
    }
    
   
	
	
	
}
