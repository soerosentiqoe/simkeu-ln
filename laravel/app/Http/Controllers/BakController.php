<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class BakController extends AppModelController {
	var $table="d_lpj_kemlu";
	var $view="v_mon_lpj";
	
	public function __construct()
	{
		
	}
	
	public function index() {
        
    }
   
    public function add(Request $request)
	{
		$data=['thang'=>session('thang'),'kdsatker'=>session('kdsatker'),'nospby'=>$request->input('nospby'),'tgspby'=>$request->input('tgspby'),'kdvalas'=>$request->input('kdvalas'),'nilai'=>$request->input('nilai'),'kpd'=>$request->input('kpd'),'uraian'=>$request->input('uraian'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput'),'kdakun'=>$request->input('kdakun'),'created_id'=>session('id'),'created_ip'=>$request->ip()];       
        $where =['nospby'=>$request->input('nospby'),'thang'=>session('thang'),'kdsatker'=>session('kdsatker')];
        if ($this->getCountByWhere($this->table,$where) > 0) {
			return response()->json(['error' =>true,'message' => 'Nomor SPBY'.$request->input('nospby').' Sudah Ada !']);
		}   else {
			DB::beginTransaction();
			DB::setDateFormat('MM/DD/YYYY');
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
        $data=['tgspby'=>$request->input('tgspby'),'kdvalas'=>$request->input('kdvalas'),'nilai'=>$request->input('nilai'),'kpd'=>$request->input('kpd'),'uraian'=>$request->input('uraian'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput'),'kdakun'=>$request->input('kdakun'),'created_id'=>session('id'),'created_ip'=>$request->ip()];       
		DB::beginTransaction();
		DB::setDateFormat('MM/DD/YYYY');
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
		$aColumns = array('id','nomor','bulan','notran','id_buku','kdvalas','saldoakhir');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id ";
		/* DB table to use */
		$sTable = $this->view;
		
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
		$sWhere = " WHERE kdsatker = '".session('kdsatker')."' and thang='".session('thang')."'";
		if (isset($_GET['sSearch'])) {
			$sSearch = strtolower($_GET['sSearch']);
			if ((isset($sSearch)) && ($sSearch != '')) {
				$sWhere = " where bulan like '" . $sSearch . "%' or lower(nomor) like '%" . $sSearch . "%' ";
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
				'<center>' . $row->bulan. '</center>',
                '<center>' . $row->nomor . '</center>',
                '<center>' . $row->notran. '</center>',
                '<center>' . $row->id_buku. '</center>',
                '<center>' . $row->kdvalas. '</center>',
                '<center>' . $row->saldoakhir. '</center>',
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
