<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class KuitansiController extends AppModelController {

	public function index() {
        
    }
    
     public function getRefById($id) {
        $table="t_ref_tran_kemlu";        
        if ($this->getById($id,$table)) {
            return response()->json(['error'=>false,'data'=>$this->getById($id,$table)]);            
        } else {
            return response()->json(['error'=>true,'data'=>'']); 
        }
        
    }
    
    public function getMaxNokwt() {
        $table='v_maxno_kuitansi';
        $where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdbpp'=>session('kdbpp')];
        $column='nokwt';    
        return response()->json(['data'=>$this->getFirstRowByWhere($table,$where,$column) == 0 ? '1' : $this->getFirstRowByWhere($table,$where,$column)]);
    }
    
    public function add(Request $request) {
        if ($this->getCountByWhere('d_kuitansi_kemlu',['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'nokwt'=>$request->input('nokwt')])  < 1) {
            $data=['kdsatker'=>session('kdsatker'),'kdbpp'=>session('kdbpp'),'kdprogram'=>$request->input('kdprogram'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput'),'kdsoutput'=>$request->input('kdsoutput'),'kdkmpnen'=>$request->input('kdkmpnen'),'kdskmpnen'=>$request->input('kdskmpnen'),'kdakun'=>$request->input('kdakun'),'nokwt'=>$request->input('nokwt'),'tglkwt'=>$request->input('tglkwt'),'kdvalas'=>$request->input('kdvalas_kuitansi'),'nilkurs'=>$request->input('nilkurs_kuitansi'),'nilkurs1'=>$request->input('nilkurs_kuitansi1'),'uraian'=>$request->input('uraian'),'rupiah'=>$request->input('rupiah'),'thang'=>session('thang'),'created_id'=>session('id'),'created_ip'=>$request->ip(),'kdbeban'=>$request->input('kdbeban'),'kdkppn'=>$request->input('kdkppn'),'register'=>$request->input('register'),'kdlokasi'=>$request->input('kdlokasi'),'kdkabkota'=>$request->input('kdkabkota'),'kddekon'=>$request->input('kddekon'),'kdjendok'=>$request->input('kdjendok'),'kdjnsban'=>$request->input('kdjnsban'),'kdctarik'=>$request->input('kdctarik'),'kdsdana'=>$request->input('kdsdana')];
            DB::beginTransaction();
            DB::setDateFormat('MM/DD/YYYY');
            if (DB::table('d_kuitansi_kemlu')->insert($data)) {
                DB::commit();
                return response()->json(['error'=>false,'message'=>'Berhasil Insert','id'=>$this->getMaxId('d_kuitansi_kemlu','id'),'nokwt'=>$request->input('nokwt'),'tglkwt'=>$request->input('tglkwt')]);
            } else {
                return response()->json(['error'=>true,'message'=>'Gagal Insert']);
            }
            
        } else {
            return response()->json(['error'=>true,'message'=>'Nomor Transaksi :'.$request->input('nokwt').'  sudah ada']); 
        }
        
    }
     public function hapus(Request $request) {
        $table="d_tran_kemlu";
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
		$aColumns = array('nokwt','uraian','rupiah','kdvalas','nilkurs1','nilkurs','kdakun');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id ";
		/* DB table to use */
		$sTable = " d_kuitansi_kemlu ";
		
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
		$sWhere = " WHERE kdsatker = '".session('kdsatker')."' and thang = '".session('thang')."' ";
		if (isset($_GET['sSearch'])) {
			$sSearch = strtolower($_GET['sSearch']);
			if ((isset($sSearch)) && ($sSearch != '')) {
				$sWhere = " where nokwt like '" . $sSearch . "%' or lower(uraian) like '%" . $sSearch . "%' ";
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

				

			$output['aaData'][] = array(
				'<center>' . $row->nokwt . '</center>',
                '<center>' . $row->uraian . '</center>',
                '<center>' . $row->rupiah . '</center>',
                '<center>' . $row->kdvalas. '</center>',
				'<center>' . $row->nilkurs. '</center>',
				'<center>' . $row->nilkurs1. '</center>',
				'<center>' . $row->kdakun. '</center>'
			);
		}
		
		return response()->json($output);		
	}
    
	


}
