<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class UserController extends AppModelController {
	var $table="t_admin";
	public function __construct()
	{
		
	}
	
	public function index() {
        
    }
    public function add(Request $request)
	{
		$data=['kdsatker'=>$request->input('kdsatker'),'kdlevel'=>$request->input('kdlevel'),'username'=>$request->input('username'),'password'=>md5($request->input('password')),'nama'=>$request->input('nama'),'nip'=>$request->input('nip'),'aktif'=>'1','created_id'=>session('id'),'created_ip'=>$request->ip()];       
        $where =['username'=>$request->input('username')];
        if ($this->getCountByWhere($this->table,$where) > 0) {
			return response()->json(['error' =>true,'message' => 'Username '.$request->input('username').' Sudah Ada !']);
		}   else {
			DB::beginTransaction();
			 if (DB::table($this->table)->insert($data) ) {             
				  DB::commit();
				 return response()->json(['error' => false,'message' => 'Insert data berhasil']);             
			 } else {
				 return response()->json(['error' =>true,'message' => 'Insert Data gagal. Silahkan hubungi Administrator']);
			 }
		
		}
        
       
		
	}
    
    public function ubah(Request $request) {
        $where=['id'=>$request->input('inp-id')];
        $data=['kdsatker'=>$request->input('kdsatker'),'kdlevel'=>$request->input('kdlevel'),'username'=>$request->input('username'),'password'=>md5($request->input('password')),'nama'=>$request->input('nama'),'nip'=>$request->input('nip'),'updated_id'=>session('id'),'updated_ip'=>$request->ip()];       
		DB::beginTransaction();
        if (DB::table($this->table)->where($where)->update($data)) {            
            DB::commit();
            return response()->json(['error' => false,'message' => 'Update data berhasil']);
            
        } else {
            return response()->json(['error' =>true,'message' => 'Update Data gagal. Silahkan hubungi Administrator']);
        }
        
    }
    public function hapus(Request $request) {
        $where=['id'=>$request->input('id')];
        DB::beginTransaction();
        if (DB::table($this->table)->where($where)->delete()) {
            DB::commit();
            return response()->json(['error' => false,'message' => 'Hapus data berhasil']);
            
        } else {
            return response()->json(['error' =>true,'message' => 'Hapus Data gagal. Silahkan hubungi Administrator']);
        }
        
    }
    
    public function monitoring()
	{
		$aColumns = array('username','nama','nip','nmlevel','aktif','id');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id ";
		/* DB table to use */
		$sTable = " v_user";
		
		/*
		 * Paging
		 */ 
		$sLimit = " ";
		if ((isset($_GET['iDisplayStart'])) && (isset($_GET['iDisplayLength']))) {
			$iDisplayStart  = $_GET['iDisplayStart']+1;
			$iDisplayLength = $_GET['iDisplayLength'];
			$sSearch		= $_GET['sSearch'];
			
			if (($sSearch == '') && (isset( $iDisplayStart )) && ($iDisplayLength != '-1')) {
				$iDisplayEnd = $iDisplayStart + $iDisplayLength - 1;
				$sLimit = " WHERE NO BETWEEN '$iDisplayStart' AND '$iDisplayEnd'";
			}
		}
		
		/*
		 * Ordering
		 */
		$sOrder = " ORDER BY id DESC";
		if ((isset($_GET['iSortCol_0'])) && (isset($_GET['sSortDir_0']))) {
			$iSortCol_0 = $_GET['iSortCol_0'];
			$iSortDir_0 = $_GET['sSortDir_0'];
			
			if (isset( $iSortCol_0 )) {		
				//modified ordering
				for($i = 0; $i < count($aColumns); $i++) {
					if ($iSortCol_0 == $i) {
						if ($iSortDir_0 == 'asc') {
							$sOrder = " ORDER BY " . $aColumns[$i] . " DESC ";
						}
						else {
							$sOrder = " ORDER BY " . $aColumns[$i] . " ASC ";
						}
					}
				}
			}
		}
		
		//modified filtering
		$sWhere = " WHERE kdsatker = '".session('kdsatker')."' ";
		if (isset($_GET['sSearch'])) {
			$sSearch = strtolower($_GET['sSearch']);
			if ((isset($sSearch)) && ($sSearch != '')) {
				$sWhere = " where username like '" . $sSearch . "%' or lower(nama) like '%" . $sSearch . "%' ";
			}
		}
		
		/* Data set length after filtering */
		$iFilteredTotal = 0;
		$rows = DB::select("
			SELECT COUNT(*) as JUMLAH FROM (".$sTable.") qry
		");
		$result = (array)$rows[0];
		if ($result) {
			$iFilteredTotal = $result['jumlah'];
		}
		
		/* Total data set length */
		$iTotal = 0;
		$rows = DB::select("
			SELECT COUNT(".$sIndexColumn.") as JUMLAH FROM (".$sTable.") qry
		");
		$result = (array)$rows[0];
		if ($result) {
			$iTotal = $result['jumlah'];
		}

		/*
		 * Format Output
		 */
		$sEcho = "";
		if (isset($_GET['sEcho'])) {
			$sEcho = $_GET['sEcho'];
		}
		$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$str = str_replace(" , ", " ", implode(", ", $aColumns));
		
		$sQuery = "SELECT * FROM ( SELECT ROWNUM AS NO," . $str . " FROM ( SELECT * FROM (" . $sTable . ") " . $sOrder . ") " . $sWhere . " ) a " . $sLimit . " ";
		
		$rows = DB::select($sQuery);
		
		foreach( $rows as $row ) {
			if ($row->id == session('id')) {
			$aksi='<center>-</center>';
			} else {
			$aksi = '<center>
							<a href="javascript:;" id="' . $row->id . '" class="btn btn-success ubah" title="Ubah"><i class="fa fa-pencil"></i></a>
							<a href="javascript:;" id="' . $row->id . '" class="btn btn-danger hapus" title="Hapus"><i class="fa fa-trash-o"></i></a>
						</center>';
			
			}			

			$output['aaData'][] = array(
				'<center>' . $row->username. '</center>',
                '<center>' . $row->nama . '</center>',
                '<center>' . $row->nip. '</center>',
                '<center>' . $row->nmlevel . '</center>',
				$aksi
			);
		}
		
		return response()->json($output);		
	}
    
    public function getDataById($id) {              
        if ($this->getById($id,$this->table)) {
            return response()->json(['error'=>false,'data'=>$this->getById($id,$this->table)]);            
        } else {
            return response()->json(['error'=>true,'data'=>'']); 
        }
        
    }
  
    
	


}
