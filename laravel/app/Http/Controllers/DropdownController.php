<?php namespace App\Http\Controllers;



use Illuminate\Http\Request;
use Session;
use DB;

class DropdownController extends Controller {
	
	public function __construct()
	{
		
	}
	
	
		public function getJenbuku()
            {
                $rows=DB::table('T_JENBUKU_KEMLU')
                    ->whereNotIn('ID',[1,2,12])
                    ->select('ID','KDBUKU','NMBUKU') 
                    ->orderBy('id', 'asc')
                    ->get();
                $htmlout = '<option value="" style="display:none;">Pilih Buku</option>';
                foreach($rows as $row) {
                    $htmlout .= '<option value="'.$row->id.'">'.$row->kdbuku.' - '.$row->nmbuku.'</option>';
                }
                echo $htmlout;
            }
    public function getValas()
            {
                $rows=DB::table('T_VALAS')
                     ->select('KDVALAS','MATAUANG','NEGARA')
                    ->get();
                $htmlout = '<option value="" style="display:none;">Pilih Valas</option>';
                foreach($rows as $row) {
                  
                      $htmlout .= '<option value="'.$row->kdvalas.'">'.$row->kdvalas.' - '.$row->matauang.' - '.$row->negara.'</option>';
                }
                echo $htmlout;
            }
    public function getRefTran()
            {
                $rows=DB::table('T_REF_TRAN_KEMLU')->get();
                $htmlout = '<option value="" style="display:none;">Pilih Transaksi</option>';
                foreach($rows as $row) {  
                    
                      $htmlout .= '<option value="'.$row->id.'">'.$row->kdtran.' - '.$row->nmtran.'</option>';
                }
                echo $htmlout;
            }
    public function getKdjenspm()
            {
                $rows=DB::table('T_JENSPM')->get();
                $htmlout = '<option value="" style="display:none;">Pilih Jenis SPM</option>';
                foreach($rows as $row) {  
                    
                      $htmlout .= '<option value="'.$row->kdjenspm.'">'.$row->kdjenspm.' - '.$row->nmjenspm.'</option>';
                }
                echo $htmlout;
            }
    public function getKdprogram() {
                $rows=DB::table('v_program')->where(['kdsatker'=>session('kdsatker'),'thang'=>session('thang')])->get();
                $htmlout = '<option value="" style="display:none;">Pilih Program</option>';
                foreach($rows as $row) {  
                    
                      $htmlout .= '<option value="'.$row->kdprogram.'">'.$row->kdprogram.' - '.$row->nmprogram.'</option>';
                }
                echo $htmlout;
 
        
    }
    public function getKdgiat(Request $request) {
				if (isset($_GET['kdprogram'])){
					$where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdprogram'=>$request->input('kdprogram')];
				} else {
					$where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang')];
					}
				$rows=DB::table('v_giat')->where($where)->get();
                $htmlout = '<option value="" style="display:none;">Pilih Kegiatan</option>';
                foreach($rows as $row) {  
                    
                      $htmlout .= '<option data-kdprogram="'.$row->kdprogram.'" value="'.$row->kdgiat.'">'.$row->kdgiat.' - '.$row->nmgiat.'</option>';
                }
                echo $htmlout;
        
    }
    public function getKdoutput(Request $request) {
				if (isset($_GET['kdprogram'])){
					$where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdprogram'=>$request->input('kdprogram'),'kdgiat'=>$request->input('kdgiat')];
					}else {
						$where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdgiat'=>$request->input('kdgiat')];
						}
				$rows=DB::table('v_output')->where($where)->get();
                $htmlout = '<option value="" style="display:none;">Pilih Output</option>';
                foreach($rows as $row) {  
                    
                      $htmlout .= '<option  value="'.$row->kdoutput.'">'.$row->kdoutput.' - '.$row->nmoutput.'</option>';
                }
                echo $htmlout;
        
    }
    public function getKdsoutput(Request $request){
         $rows=DB::table('v_soutput')->where(['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdprogram'=>$request->input('kdprogram'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput')])->get();
                $htmlout = '<option value="" style="display:none;">Pilih Sub Output</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdsoutput.'">'.$row->kdsoutput.' - '.$row->ursoutput.'</option>';
                }
                echo $htmlout;
        
    }
    public function getKdkmpnen(Request $request) {
         $rows=DB::table('v_kmpnen')->where(['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdprogram'=>$request->input('kdprogram'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput'),'kdsoutput'=>$request->input('kdsoutput')])->get();
                $htmlout = '<option value="" style="display:none;">Pilih Komponen</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdkmpnen.'">'.$row->kdkmpnen.' - '.$row->urkmpnen.'</option>';
                }
                echo $htmlout;
        
    }
     public function getKdskmpnen(Request $request) {
         $rows=DB::table('v_skmpnen')->where(['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdprogram'=>$request->input('kdprogram'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput'),'kdsoutput'=>$request->input('kdsoutput'),'kdkmpnen'=>$request->input('kdkmpnen')])->get();
                $htmlout = '<option value="" style="display:none;">Pilih Sub Komponen</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdskmpnen.'">'.$row->kdskmpnen.' - '.$row->urskmpnen.'</option>';
                }
                echo $htmlout;
        
    }
      public function getKdakun(Request $request) {
				if (isset($_GET['kdprogram']) && isset($_GET['kdsoutput']) && isset($_GET['kdkmpnen']) && isset($_GET['kdskmpnen'])){
					$where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdprogram'=>$request->input('kdprogram'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput'),'kdsoutput'=>$request->input('kdsoutput'),'kdkmpnen'=>$request->input('kdkmpnen'),'kdskmpnen'=>$request->input('kdskmpnen')];
					} else {
					$where=['kdsatker'=>session('kdsatker'),'thang'=>session('thang'),'kdgiat'=>$request->input('kdgiat'),'kdoutput'=>$request->input('kdoutput')];
					}
				$rows=DB::table('v_kdakun')->where($where)->get();
                $htmlout = '<option value="" style="display:none;">Pilih Akun</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option data-paguakhir="'.$row->paguakhir.'" data-nilblokir="'.$row->nilblokir.'" value="'.$row->kdakun.'">'.$row->kdakun.' - '.$row->nmakun.'</option>';
                }
                echo $htmlout;
        
    }
       public function getBulan(Request $request) {
				$rows=DB::table('t_bulan')->get();
                $htmlout = '<option value="" style="display:none;">Pilih Bulan</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdbulan.'">'.$row->kdbulan.' - '.$row->nmbulan.'</option>';
                }
                echo $htmlout;
        
    }
      public function getKdlevel(Request $request) {
		  if (session('kdlevel') == '00') {
			$rows=DB::table('t_level')->whereNotIn('kdlevel',['00'])->get();
		  } else {
			if (session('kdlevel') == '01') {
			$rows=DB::table('t_level')->whereNotIn('kdlevel',['00','01'])->get();
			} 
		  }
         
                $htmlout = '<option value="" style="display:none;">Pilih Kewenangan</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdlevel.'">'.$row->nmlevel.'</option>';
                }
                echo $htmlout;
        
    }
     public function getKdsatker(Request $request) {
		  if (session('kdlevel') == '00') {
			$rows=DB::table('t_satker')->select('kdsatker','nmsatker')->get();
		  } else {
			
			$rows=DB::table('t_satker')->select('kdsatker','nmsatker')->where('kdsatker','=',session('kdsatker'))->get();
			
		  }
         
                $htmlout = '<option value="" style="display:none;">Pilih Satuan Kerja</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdsatker.'">'.$row->nmsatker.'</option>';
                }
                echo $htmlout;
        
    }
    public function getGroupMenu(Request $request) {
		  		$rows=DB::table('group_menu')->select('id','nama_group_menu')->orderBy('urut','asc')->get();
		        $htmlout = '<option value="" style="display:none;">Pilih Group Menu</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->id.'">'.$row->nama_group_menu.'</option>';
                }
                echo $htmlout;
        
    }
    public function getMenu(Request $request) {
		  		$rows=DB::table('t_menu')->orderBy('urut','asc')->where('id_group_menu','=',$request->input('id_group_menu'))->select('id','nmmenu')->get();
		        $htmlout = '<option value="" style="display:none;">Pilih Group Menu</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->id.'">'.$row->nmmenu.'</option>';
                }
                echo $htmlout;
        
    }
     public function getJenisSppGu(Request $request) {
		  		$rows=DB::table('t_jenis_sppgu')->orderBy('kdjns','asc')->select('kdjns','nmjns')->get();
		        $htmlout = '<option value="" style="display:none;">Pilih Group Menu</option>';
                foreach($rows as $row) {
                      $htmlout .= '<option  value="'.$row->kdjns.'">'.$row->nmjns.'</option>';
                }
                echo $htmlout;
        
    }
    
	
}
