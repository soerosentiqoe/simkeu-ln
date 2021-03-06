<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class SppController extends AppModelController {

	
	public function __construct()
	{
		
	}

	public function index()
	{
    }
		    
    
    
    public function monitoring()
	{
		$aColumns = array('nospp','tgspp','kdvalas','nilkurs','totnilmak','totnilmap');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "nospp";
        $sTable="d_spmind";
		
		
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
		$sOrder = " ORDER BY tgspp,nospp ASC";
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
        if (!isset($_GET['bulan'])  && !isset($_GET['tahun'])) {
            $sWhere=" WHERE kdsatker ='".session('kdsatker')."'  and thang='".session('thang')."' and 1=0 ";
            if(isset($_GET['sSearch'])){
			$sSearch=$_GET['sSearch'];
			if((isset($sSearch))&&($sSearch!='')){
				$sWhere=" where nospp like '".$sSearch."%' or totnilmak like '%".$sSearch."%' ";
			}
		}
        } else {
            $sWhere=" WHERE kdsatker ='".session('kdsatker')."' and kdbpp='".session('kdbpp')."' and thang='".session('thang')."' and  to_char(tgspp, 'MM')='".$_GET['bulan']."'";
            if(isset($_GET['sSearch'])){
			$sSearch=$_GET['sSearch'];
			if((isset($sSearch))&&($sSearch!='')){
				$sWhere=" where nospp like '".$sSearch."%' totnilmak like '%".$sSearch."%' ";
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
		
		
			$output['aaData'][] = array(
				$row->nospp,
                $row->tgspp,
                $row->kdvalas,
                $row->nilkurs,
                $row->totnilmak,
                $row->totnilmap,
                ($row->totnilmak-$row->totnilmap)
                
			);
		}
		
		return response()->json($output);
	}
    
   
	
	

}
