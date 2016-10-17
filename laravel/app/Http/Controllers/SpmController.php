<?php namespace App\Http\Controllers;

use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use ZipArchive;
use Schema;
use App\Http\Controllers\DbfController;


class SpmController extends DbfController {

	
   
	public function __construct()
	{
       
		
	}

	
	public function index()
	{
		
	}
    public function process(Request $request) {
         $id=Input::get('idfile');
          $datas= $this ->getById($id,'t_file');
         foreach($datas as $data) {
             $dataFile=$data->strorage;
             $targetFolder=$data->folder.'temp/'.session('kdsatker').'/';
             
         }
        if (!file_exists($targetFolder)) {
				mkdir($targetFolder , 0777, true);
				}
        $namafile=array('d_spmind.dbf','d_spminfo.dbf','d_spmmak.dbf','d_spmmap.dbf','dd_spmmak.dbf');
        $table=array('d_spmind','d_spminfo','d_spmmak','d_spmmap','dd_spmmak');
        $pass="indahLaminatingrum";
        exec("unzip -P " . $pass . " " . $dataFile . " -d ".$targetFolder." ");
        $hasil=[];
       for($x=0;$x<count($namafile);$x++)  {
           $hasil[$x]['name_file']=$table[$x];
             if (file_exists($targetFolder.$namafile[$x])) {
                  $data=$this->read_file_dbf($targetFolder.$namafile[$x]);
                 $hasil[$x]['exist']=1;
                 $hasil[$x]['data']=$data;
                $hasil[$x]['num_record']=count($data);
                 if (count($data) > 0 ) {
                     foreach($data as $where){
                       
                         $key_data=array_keys($where);       
                            foreach($key_data as $key) {
                               if (!Schema::hasColumn($table[$x], $key)) {               
                                    unset($where[$key]);
                               }
                            }
                         //print_r($where);
                            
                           $wherein=['kdsatker'=>$where['KDSATKER'],'thang'=>$where['THANG'],'nospm'=>$where['NOSPM']];  
                       if ($this->getCountByWhere($table[$x],$wherein)  > 1) {
                            DB::beginTransaction();
                            DB::table($table[$x])->where($wherein)->delete();
                            DB::commit(); 
                             
                         } 
                        DB::beginTransaction();
                        DB::setDateFormat('YYYYMMDD');
                         if (DB::table($table[$x])->insert($where)) {
                             DB::commit();                              
                              $hasil[$x]['insert']=1;
                         } else {
                             
                         }
                     }
                     
                 } else {
                     $hasil[$x]['insert']=0;
                     
                 }

                } else {
                    $hasil[$x]['exist']=0;
                }
        }
        return response()->json(['data'=>$hasil]);
     /*   for($x=0;$x<count($namafile);$x++)  {
                             if (file_exists($targetFolder.$namafile[$x])) {
                                   chmod($targetFolder.$namafile[$x],0600); 
                                    chmod($targetFolder.$namafile[$x],0644);
                                    chmod($targetFolder.$namafile[$x],0755);
                                    chmod($targetFolder.$namafile[$x],0750); 
                                    chmod($targetFolder.$namafile[$x],0777); 
                                    //chown($targetFolder.$namafile[$x], 'SITP');
                                    if (!dbase_open($targetFolder.$namafile[$x], 2)) {                                 
									return response()->json(array('error'=>true,'message'=>'PHP DBASE gagal open'));                                                                                
                                    } else {
                                        $db = dbase_open($targetFolder.$namafile[$x], 2);
                                        $record_numbers = dbase_numrecords($db); 
                                        for ($i = 1; $i <= $record_numbers; $i++) {
                                          //for ($i = 1; $i <= 2; $i++) {
                                              $row = dbase_get_record_with_names($db, $i);
                                              $cols = (array)$row;  
                                            
                                                array_push($data,[$table[$x]=>$cols]);
                                           
                                          }
                                        dbase_close($db);
                                         //unlink($targetFolder.$namafile[$x]);
                                          
                                    }

                                } else {
                                    return response()->json(array('error'=>true,'message'=>'File '.$namafile1.'Tidak ada'));
                                }
                        }*/
        
       /* for($x=0;$x<count($namafile);$x++) {
            //$arr=['id'];
            $def = array(
                          array("date",     "D"),
                          array("name",     "C",  50),
                          array("age",      "N",   3, 0),
                          array("email",    "C", 128),
                          array("ismember", "L")
                        );
            dbase_create($targetFolder.$namafile[$x],$def );
            /*fopen($targetFolder.$namafile[$x],"w+");
            unlink($targetFolder.$namafile[$x]);
            if (file_exists($targetFolder.$namafile[$x])) {
                chown($targetFolder.$namafile[$x], 666);
                chmod($targetFolder.$namafile[$x],0600); 
                chmod($targetFolder.$namafile[$x],0644);
                chmod($targetFolder.$namafile[$x],0755);
                chmod($targetFolder.$namafile[$x],0750);
                unlink($targetFolder.$namafile[$x]);              
            
            }
        }*/
       /*
        $zip = new ZipArchive();
		$zip_status = $zip->open($dataFile);
        if ($zip_status === true)
		{
            if ($zip->setPassword("indahLaminatingrum"))
			{
                    if (!$zip->extractTo($targetFolder,$namafile)) {
                        return response()->json(array('error'=>true,'message'=>'extract '.$targetFolder.' gagal'));                                         
                    } else {
                        $data=[]; 
                        $zip->close(); 
                        for($x=0;$x<count($namafile);$x++)  {
                            //for($x=0;$x<3;$x++)  {
                             if (file_exists($targetFolder.$namafile[$x])) {
                                 /*  chmod($targetFolder.$namafile[$x],0600); 
                                    chmod($targetFolder.$namafile[$x],0644);
                                    chmod($targetFolder.$namafile[$x],0755);
                                    chmod($targetFolder.$namafile[$x],0750); 
                                    chmod($targetFolder.$namafile[$x],0777); 
                                    chown($targetFolder.$namafile[$x], 'SITP');*/
                               /*  dbase_open($targetFolder.$namafile[$x], 0);
                                 dbase_open($targetFolder.$namafile[$x], 1);
                                     dbase_open($targetFolder.$namafile[$x], 2);*/
                                    /*if (!dbase_open($targetFolder.$namafile[$x], 2)) {                                      return response()->json(array('error'=>true,'message'=>'PHP DBASE gagal open'));
                                                                                
                                    } else {
                                        $db = dbase_open($targetFolder.$namafile[$x], 2);
                                        $record_numbers = dbase_numrecords($db); 
                                        for ($i = 1; $i <= $record_numbers; $i++) {
                                        
                                              $row = dbase_get_record_with_names($db, $i);
                                              $cols = (array)$row;
                                            
                                                array_push($data,[$table[$x]=>$cols]);
                                           
                                          }
                                        dbase_close($db);
                                     
                                          
                                    }

                                } else {
                                    return response()->json(array('error'=>true,'message'=>'File '.$namafile1.'Tidak ada'));
                                }
                        }
                        return response()->json(array('error'=>false,'message'=>'Berhasil Extract','data'=>$data));
                             
                            
                        }
                
            }else {
                 return response()->json(array('error'=>true,'message'=>'zip Password Salah'));
                
            }
                
        } else {
             return response()->json(array('error'=>true,'message'=>'ZIP open '.$dataFile.' gagal'));
            
        }
    */
    }
   /* private function saveData($data,$table) {
        
        DB::beginTransaction();
        DB::setDateFormat(('MM/DD/YYYY'));
		DB::table($table)->insert($data);
        DB::commit();
              
    }
    private  function getMaxId($table,$column) {
        return DB::table($table)->max($column);
    }
    private function getById($id,$table){
        return DB::table($table)
		->	where (array('id'=>$id ))
		->get();
    }*/
    private function getColumnType($table,$column) {
      $raws= DB::select("select column_name, data_type from user_tab_columns where table_name = ? and column_name=?",[strtoupper($table),strtoupper($column)]);
       foreach($raws as $raw) {
           $raw=$raw->data_type;
       }
        return $raw;

    }
    private function mySimpan($data,$table) {
      
        $key_data=array_keys($data);
       
        foreach($key_data as $key) {
            
            
           if (!Schema::hasColumn($table, $key)) {               
                unset($data[$key]);
           } else {
               $type=$this->getColumnType($table,$key);
               if ($type == "DATE" ) {
                   if ($data[$key] === NULL || $data[$key] || $data[$key] ==0 ) {
                       $data[$key]=date('m/d/Y');
                   } else {
                       $tahun=substr($data[$key],0,3);
                       $bulan=substr($data[$key],4,2);
                       $hari=substr($data[$key],7,2);
                       echo $data[$key];
                       $tanggal=$bulan."/".$hari."/".$tahun;
                       $data[$key]=date('m/d/Y',strtotime($tanggal));
                   }
                   
               } 
               
           }
            
        }
        //print_r($data);
        //$this->saveData($data,$table);
        //print_r($data);
        
        
    }
    
    
    public function monitoring() {
        $aColumns = array('nospm','tgspm','kdvalas','nilkurs','totnilmak','totnilmap');
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "nospm";
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
		$sOrder = " ORDER BY tgspm,nospm ASC";
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
            $sWhere=" WHERE kdsatker ='".session('kdsatker')."'  and thang='".session('thang')."' and 1=0  and nospm<>'' ";
            if(isset($_GET['sSearch'])){
			$sSearch=$_GET['sSearch'];
			if((isset($sSearch))&&($sSearch!='')){
				$sWhere=" where nospm like '".$sSearch."%' or totnilmak like '%".$sSearch."%' ";
			}
		}
        } else {
            $sWhere=" WHERE kdsatker ='".session('kdsatker')."' and kdbpp='".session('kdbpp')."' and thang='".session('thang')."' and  to_char(tgspp, 'MM')='".$_GET['bulan']."'";
            if(isset($_GET['sSearch'])){
			$sSearch=$_GET['sSearch'];
			if((isset($sSearch))&&($sSearch!='')){
				$sWhere=" where nospm like '".$sSearch."%' totnilmak like '%".$sSearch."%' ";
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
				$row->nospm,
                $row->tgspm,
                $row->kdvalas,
                $row->nilkurs,
                $row->totnilmak,
                $row->totnilmap,
                ($row->totnilmak-$row->totnilmap)
                
			);
		}
		
		return response()->json($output);		
    }
    
    public function getMaxNilkurs() {
        return response()->json(['error'=>false,'data'=>DB::table('v_max_nilkurs')->where(['kdsatker'=>session('kdsatker'),'kdbpp'=>session('kdbpp'),'thang'=>session('thang')])->first()]);
    }
    
	
	
	
	
}
