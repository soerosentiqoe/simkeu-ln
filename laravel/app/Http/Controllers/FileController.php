<?php namespace App\Http\Controllers;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\AppModelController;

class FileController extends AppModelController {


public function upload(Request $request) {
             $ip=$request->ip();
	$session=Session::all();
        
       /* try {*/
                $targetFolder =storage_path().'/'.$request->input('folder').'/'.session('thang').'/'.session('kdsatker').'/'; // Relative to the root
				if (!file_exists($targetFolder )) {
				mkdir($targetFolder , 0777, true);
				}
					
					$tempFile = $_FILES['file']['tmp_name'];
					$targetPath = dirname(__FILE__) . '/' . $targetFolder;
                    $namafile=$_FILES['file']['name'];
                    $fileParts = pathinfo($_FILES['file']['name']);					
					$targetFile=$targetFolder .$namafile;               
                    
                    move_uploaded_file($tempFile,$targetFile);
                    if (file_exists($targetFile)) {                       
                        $data=array('nmfile'=>$namafile,'extension'=>$fileParts['extension'],'strorage'=>$targetFile,'created_id'=>$session['id'],'created_ip'=>$ip,'folder'=>$targetFolder);
                        DB::beginTransaction();
                       if (DB::table('t_file')->insert($data)) {
                           DB::commit();
                           return response()->json(array('error'=>false,'message'=>'Berhasil di upload dan insert tabel file','idfile'=>$this->getMaxId('t_file','id')));
                       } else {
                           return response()->json(array('error'=>true,'message'=>'Gagal di insert tabel file'));
                       }
                        
                        return response()->json(array('error'=>false,'message'=>'Berhasil di upload dan insert tabel file'));
                    } else {
                      return response()->json(array('error'=>true,'message'=>'Gagal di upload'));  
                    }
                    
            
     /*  } catch (\Exception $e) {
          return response()->json(array('error'=>true,'message'=>$e));
        }*/
    }
    



}
