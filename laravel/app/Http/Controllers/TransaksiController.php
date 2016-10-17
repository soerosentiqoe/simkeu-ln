<?php namespace App\Http\Controllers;

use DB;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class TransaksiController extends AppModelController {

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
    
    
    public function getMaxNotran() {
        $table='v_max_notran';
        $where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdbpp'=>session('kdbpp')];
        $column='notran';    
        return response()->json(['data'=>$this->getFirstRowByWhere($table,$where,$column) == 0 ? '1' : $this->getFirstRowByWhere($table,$where,$column)]);
    }
    
    public function add(Request $request) {
        if ($this->getCountByWhere('d_tran_kemlu',['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'notran'=>$request->input('notran')])  < 1) {
            $data=['kdsatker'=>session('kdsatker'),'kdbpp'=>session('kdbpp'),'notran'=>$request->input('notran'),'tgtran'=>$request->input('tgtran'),'id_ref_tran'=>$request->input('id_ref_tran'),'kdtran'=>$request->input('kdtran'),'nmtran'=>$request->input('nmtran'),'kdvalas'=>$request->input('kdvalas'),'kdvalas1'=>$request->input('kdvalas1'),'nilkurs'=>$request->input('nilkurs'),'nilkurs1'=>$request->input('nilkurs1'),'norek'=>$request->input('norek'),'niltran'=>$request->input('niltran'),'thang'=>session('thang'),'created_id'=>session('id'),'created_ip'=>$request->ip(),'id_spm'=>$request->input('id_spm'),'cara_bayar'=>$request->input('cara_bayar'),'id_kuitansi'=>$request->input('id_kuitansi')];
            DB::beginTransaction();
            DB::setDateFormat('MM/DD/YYYY');
            if (DB::table('d_tran_kemlu')->insert($data)) {
                DB::commit();
                return response()->json(['error'=>false,'message'=>'Berhasil Insert']);
            } else {
                return response()->json(['error'=>true,'message'=>'Gagal Insert']);
            }
            
        } else {
            return response()->json(['error'=>true,'message'=>'Nomor Transaksi :'.$request->input('notran').'  sudah ada']); 
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
		$aColumns = array('notran','kdvalas','norek','niltran','id','nmtran','tgtran');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id ";
		/* DB table to use */
		$sTable = " d_tran_kemlu ";
		
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
				$sWhere = " where notran like '" . $sSearch . "%' or lower(nmtran) like '%" . $sSearch . "%' ";
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
				'<center>' . $row->notran . '</center>',
                '<center>' . $row->nmtran . '</center>',
                '<center>' . $row->kdvalas . '</center>',
                '<center>' . number_format($row->niltran) . '</center>',
				$aksi
			);
		}
		
		return response()->json($output);		
	}
    
	


}
