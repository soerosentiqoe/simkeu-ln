<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class RuleGroupMenuController extends AppModelController {
	
	public function __construct()
	{
		
	}
	
	public function index() {
        
    }
    public function add(Request $request)
	{
		$data=['kdlevel'=>$request->input('kdlevel'),'id_group_menu'=>$request->input('id_group_menu'),'id_menu'=>$request->input('id_menu'),'created_id'=>session('id'),'created_ip'=>$request->ip()];       
        $where =['kdlevel'=>$request->input('kdlevel'),'id_group_menu'=>$request->input('id_group_menu'),'id_menu'=>$request->input('id_menu')];
        if ($this->getCountByWhere('t_rule_group_menu',$where) > 0) {
			return response()->json(['error' =>true,'message' => 'Data Sudah Ada !']);
		}   else {
			DB::beginTransaction();
			 if (DB::table('t_rule_group_menu')->insert($data) ) {             
				  DB::commit();
				 return response()->json(['error' => false,'message' => 'Insert data berhasil']);             
			 } else {
				 return response()->json(['error' =>true,'message' => 'Insert Data gagal. Silahkan hubungi Administrator']);
			 }
		
		}
        
       
		
	}
    
   
    public function hapus(Request $request) {
        $table="t_rule_group_menu";
        $where=['id'=>$request->input('id')];
        DB::beginTransaction();
        if (DB::table($table)->where($where)->delete()) {
            DB::commit();
            return response()->json(['error' => false,'message' => 'Hapus data berhasil']);
            
        } else {
            return response()->json(['error' =>true,'message' => 'Hapus Data gagal. Silahkan hubungi Administrator']);
        }
        
    }
    
    public function monitoring()
	{
		$aColumns = array('nmlevel','nama_group_menu','nmmenu','id');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id ";
		/* DB table to use */
		$sTable = " v_rule_group_menu";
		
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
		$sWhere = "  ";
		if (isset($_GET['sSearch'])) {
			$sSearch = strtolower($_GET['sSearch']);
			if ((isset($sSearch)) && ($sSearch != '')) {
				$sWhere = " where nmlevel like '" . $sSearch . "%' or lower(nmmenu) like '%" . $sSearch . "%' ";
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
	
			$aksi='<center>-</center>';			
			$aksi = '<center>							
							<a href="javascript:;" id="' . $row->id . '" class="btn btn-danger hapus" title="Hapus"><i class="fa fa-trash-o"></i></a>
						</center>';
			
						

			$output['aaData'][] = array(
				'<center>' . $row->nmlevel. '</center>',
                '<center>' . $row->nama_group_menu . '</center>',
                '<center>' . $row->nmmenu. '</center>',
                $aksi
			);
		}
		
		return response()->json($output);		
	}
    
    public function getDataById($id) {
              
        if ($this->getById($id,'t_rule_group_menu')) {
            return response()->json(['error'=>false,'data'=>$this->getById($id,'t_admin')]);            
        } else {
            return response()->json(['error'=>true,'data'=>'']); 
        }
        
    }
  
    
	


}
