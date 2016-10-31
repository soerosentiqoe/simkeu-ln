<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ZipArchive;
use Excel;
use App\Http\Controllers\AppModelController;

class BukuController extends AppModelController {

	var $table="d_buku_kemlu";
	var $view="v_buku_kemlu";
    var $view_distinct_kdvalas='v_distinct_kdvalas_buku';
	public function __construct()
	{
		
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
    }
		
    public function dropdownDistinctKdvalas(Request $request){
        $rows=DB::table($this->view_distinct_kdvalas)
                     ->select('KDVALAS')
                    ->where(['thang'=>session('thang'),'kdsatker'=>session('kdsatker'),'bulan'=>$request->input('bulan')])
                    ->get();
                $htmlout = '<option value="" style="display:none;">Pilih Valas</option>';
                foreach($rows as $row) {
                  
                      $htmlout .= '<option value="'.$row->kdvalas.'">'.$row->kdvalas.'</option>';
                }
                echo $htmlout;
    }
    public function hitungBukuLpj(Request $request){
        $rows=DB::table('v_saldo_buku_penambahan t1')
            ->leftJoin('t_jenbuku_kemlu t2','t1.id_jenbuku','=','t2.id')
            ->leftJoin(DB::raw('(SELECT ID_JENBUKU,SUM(SALDO) AS SALDO FROM V_SALDO_BUKU_PER_BULAN WHERE BULAN < '.$request->input('bulan').' GROUP BY ID_JENBUKU) t3'),function($join){
                $join->on('t1.id_jenbuku','=','t3.id_jenbuku');                
            })
            ->leftJoin(DB::raw('(SELECT ID_JENBUKU,SUM(SALDO) AS SALDO FROM V_SALDO_BUKU_PER_BULAN WHERE BULAN <= '.$request->input('bulan').' GROUP BY ID_JENBUKU) t4'),function($join){
                $join->on('t1.id_jenbuku','=','t4.id_jenbuku');
            })
            ->select('t1.id_jenbuku','t2.nmbuku','t1.kdvalas','t3.saldo AS saldoawal','t1.penambahan','t1.pengurangan','t4.saldo as saldoakhir')
            ->where(['t1.bulan'=>$request->input('bulan')])
            ->get();
        return response()->json(['data'=>$rows]);
    }
    public function maxNoBuku(Request $request){
        $data=DB::table('v_max_nobuku')
            ->where(['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'bulan'=>$request->input('bulan')])
            ->select('notran')
            ->get();
        return response()->json(['data'=>$data]);
        
    }
    
    
    public function monitoring()
	{
		$aColumns = array('nmtran',"tgtran",'notran','saldo','ket','kdvalas','lvl');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "notran";
        $sTable="v_buku_kemlu";
		
		
		/*
		 * Paging
		 */ 
		$sLimit = " ";
		if((isset($_GET['iDisplayStart']))&&(isset($_GET['iDisplayLength']))){
			$iDisplayStart=$_GET['iDisplayStart']+1;
			$iDisplayLength=$_GET['iDisplayLength'];
			$sSearch=$_GET['sSearch'];
			if (($sSearch=='') && (isset( $iDisplayStart )) &&  ($iDisplayLength != '-1' )) 
			{
				$iDisplayEnd=$iDisplayStart+$iDisplayLength-1;
				$sLimit = " WHERE NO BETWEEN '$iDisplayStart' AND '$iDisplayEnd'";
			}
		}
		
		/*
		 * Ordering
		 */
		$sOrder = " ORDER BY tgtran,notran ASC";
		if((isset($_GET['iSortCol_0']))&&(isset($_GET['sSortDir_0']))){
			$iSortCol_0=$_GET['iSortCol_0'];
			$iSortDir_0=$_GET['sSortDir_0'];
			if ( isset($iSortCol_0  ) )
			{		
				//modified ordering
				for($i=0;$i<count($aColumns);$i++){
					if($iSortCol_0==$i){
						if($iSortDir_0=='asc'){
							$sOrder = " ORDER BY ".$aColumns[$i]." DESC ";
						}
						else{
							$sOrder = " ORDER BY ".$aColumns[$i]." ASC ";
						}
					}
				}
			}
		}
       // $sOrder = " ORDER BY lvl,kdprogram,kdgiat,kdoutput,kdsoutput,kdkmpnen,kdskmpnen,kdakun ASC";
		
		//modified filtering
        if (!isset($_GET['id_jenbuku'])) {
            $sWhere=" WHERE kdsatker ='".session('kdsatker')."' and kdbpp='".session('kdbpp')."' and thang='".session('thang')."' and 1=0 ";
            if(isset($_GET['sSearch'])){
			$sSearch=$_GET['sSearch'];
			if((isset($sSearch))&&($sSearch!='')){
				$sWhere=" where nmtran like '".$sSearch."%' or notran like '%".$sSearch."%' ";
			}
		}
        } else {
            $sWhere=" WHERE kdsatker ='".session('kdsatker')."' and kdbpp='".session('kdbpp')."' and thang='".session('thang')."' and id_jenbuku ='".$_GET['id_jenbuku']."' and to_char(tgtran, 'MM')='".$_GET['bulan']."'";
            if(isset($_GET['sSearch'])){
			$sSearch=$_GET['sSearch'];
			if((isset($sSearch))&&($sSearch!='')){
				$sWhere=" where nmtran like '".$sSearch."%' or notran like '%".$sSearch."%' ";
			}
		}
            
        }
        //echo $sWhere;
		
		
		
		/* Data set length after filtering */
		$iFilteredTotal = 0;
		$rows = DB::select("
			SELECT COUNT(*) as JUMLAH FROM (".$sTable.") ".$sWhere."
		");
		$result = (array)$rows[0];
		if($result){
			$iFilteredTotal = $result['jumlah'];
		}
		
		/* Total data set length */
		$iTotal = 0;
		$rows = DB::select("
			SELECT COUNT(".$sIndexColumn.") as JUMLAH FROM (".$sTable.") ".$sWhere."
		");
		$result = (array)$rows[0];
		if($result){
			$iTotal = $result['jumlah'];
		}

		/*
		 * Format Output
		 */
		$sEcho="";
		if(isset($_GET['sEcho'])){
			$sEcho=$_GET['sEcho'];
		}
		$output = array(
			"sEcho" => intval($sEcho),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$str=str_replace(" , ", " ", implode(", ", $aColumns));
        
		
		$sQuery = "SELECT * FROM ( SELECT ROWNUM AS NO,".$str." FROM ( SELECT * FROM (".$sTable.") ".$sOrder.") ".$sWhere." ) a ".$sLimit." ";
		//echo $sQuery;
		$rows = DB::select($sQuery);
		
		$saldo=0;
		foreach( $rows as $row )
		{            
		
			$debet=$row->ket == 'K' ? 0:$row->saldo;
            $kredit=$row->ket == 'D' ?0 : $row->saldo;
            $saldo = $saldo +($debet - $kredit);
			$output['aaData'][] = array(
				$row->notran,
                $row->tgtran,
                $row->nmtran,
                $row->kdvalas,
                $debet,
                $kredit,
                $saldo
                
			);
		}
		
		return response()->json($output);
	}
    
   
	
	

}