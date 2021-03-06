<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class SaldoawalController extends AppModelController {

	//index
   /* protected $appmodel;
    public function __construct() {
        $this->appmodel=(new) AppModelController();
        
    }*/
	public function index() {
        
    }
    public function add(Request $request)
	{
		$data=array('kdsatker'=>session('kdsatker'),'id_jenbuku'=>$request->input('id_jenbuku'),'norek'=>$request->input('norek'),'kdvalas'=>$request->input('kdvalas'),'nilsaldo'=>$request->input('nilsaldo'),'created_id'=>session('id'),'created_ip'=>$request->ip(),'id2'=>0,'thang'=>session('thang'),'kdbpp'=>session('kdbpp'));       
        $table='d_saldoawal_kemlu';
        $column='id';
        $jenbuku_kas=array(3,4);
        $kas_besi=[11];
        DB::beginTransaction();
		 if (DB::table($table)->insert($data) ) {
             $data['id2']=$this->getMaxId($table,$column);
             if (in_array($data['id_jenbuku'],$jenbuku_kas)) {
               $data['id_jenbuku']=2;
                DB::table($table)->insert($data);                 
             }
             if (in_array($data['id_jenbuku'],$kas_besi)) {
               $data['id_jenbuku']=12;
                DB::table($table)->insert($data);                 
             }
             $column='kolom';
             $where=array('id_jenbuku'=>$request->input('id_jenbuku'));
             $table2='t_ref_lpj_kemlu';
             if ($this->getFirstRowByWhere($table2,$where,$column) == 1) {
                 $data['id_jenbuku']=1;
                 DB::table($table)->insert($data);
             }
              DB::commit();
             return response()->json(['error' => false,'message' => 'Insert data berhasil']);
           
             
         } else {
             return response()->json(['error' =>true,'message' => 'Insert Data gagal. Silahkan hubungi Administrator']);
         }
       
		
	}
    
    public function ubah(Request $request) {
        $table="d_saldoawal_kemlu";
        $where=['id'=>$request->input('inp-id')];
        $where2=['id2'=>$request->input('inp-id')];
        $data=array('norek'=>$request->input('norek'),'kdvalas'=>$request->input('kdvalas'),'nilsaldo'=>$request->input('nilsaldo'),'update_id'=>session('id'),'update_ip'=>$request->ip());
        DB::beginTransaction();
        if (DB::table($table)->where($where)->update($data)) {
            DB::table($table)->where($where2)->update($data);
            DB::commit();
            return response()->json(['error' => false,'message' => 'Update data berhasil']);
            
        } else {
            return response()->json(['error' =>true,'message' => 'Update Data gagal. Silahkan hubungi Administrator']);
        }
        
    }
    public function hapus(Request $request) {
        $table="d_saldoawal_kemlu";
        $where=['id'=>$request->input('id')];
        $where2=['id2'=>$request->input('id')];
        DB::beginTransaction();
        if (DB::table($table)->where($where)->delete()) {
            DB::table($table)->where($where2)->delete();
            DB::commit();
            return response()->json(['error' => false,'message' => 'Hapus data berhasil']);
            
        } else {
            return response()->json(['error' =>true,'message' => 'Hapus Data gagal. Silahkan hubungi Administrator']);
        }
        
    }
    
    public function monitoring()
	{
		$aColumns = array('nmbuku','kdvalas','norek','nilsaldo','id');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id ";
		/* DB table to use */
		$sTable = " v_saldoawal_kemlu ";
		
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
				$sWhere = " where norek like '" . $sSearch . "%' or lower(nmbuku) like '%" . $sSearch . "%' ";
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
							<a href="javascript:;" id="' . $row->id . '" class="btn btn-success ubah" title="Ubah"><i class="fa fa-pencil"></i></a>
							<a href="javascript:;" id="' . $row->id . '" class="btn btn-danger hapus" title="Hapus"><i class="fa fa-trash-o"></i></a>
						</center>';
			

			$output['aaData'][] = array(
				'<center>' . $row->nmbuku . '</center>',
                '<center>' . $row->kdvalas . '</center>',
                '<center>' . $row->norek . '</center>',
                '<center>' . $row->nilsaldo . '</center>',
				$aksi
			);
		}
		
		return response()->json($output);		
	}
    
    public function getDataById($id) {
        $table="d_saldoawal_kemlu";        
        if ($this->getById($id,$table)) {
            return response()->json(['error'=>false,'data'=>$this->getById($id,$table)]);            
        } else {
            return response()->json(['error'=>true,'data'=>'']); 
        }
        
    }
  
    
	


}
