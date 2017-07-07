<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
    protected $table = 'module';
    protected $primaryKey = 'module_id';
    public $timestamps  = false;
    const FOLDER = 'module';
    protected $fillable = array(
        'module_id',
        'module_title',
        'module_controller',
        'module_action',
        'module_status',
        'module_order_no',
        'module_created');

    public static function removeCache($id=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_MODUL);
            Cache::forget(\Memcaches::CACHE_MODUL_ID.$id);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('module_id','>',0);
            if(isset($search['module_title'])&&$search['module_title']!=''){
                $query->where('module_title','like','%'.$search['module_title'].'%');
            }
            if(isset($search['module_status'])&&$search['module_status']!=-1){
                $query->where('module_status',$search['module_status']);
            }
            $total = $query->count();
            $query->orderby('module_order_no','asc');
            $fil_get = isset($search['fil_get'])&&$search['fil_get']!=''?explode(',',trim($search['fil_get'])):array();
            if (!empty($fil_get)){
                $rs = $query->take($limit)->skip($offset)->get($fil_get);
            }else{
                $rs = $query->take($limit)->skip($offset)->get();
            }
            return $rs;
        }catch (\PDOException $e){
            throw new \PDOException();
        }
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_MODUL_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_MODUL_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_MODUL):array();
        if (empty($rs)){
            try{
                $query = self::where('module_id','>',0);
                $query->where('module_status',\CGlobal::status_show);
                $query->orderby('module_order_no','asc');
                $fil_get = isset($dataget['fil_get'])&&$dataget['fil_get']?explode(',',trim($dataget['fil_get'])):array();
                if ($limit>0){
                    $query->take($limit);
                }
                if (!empty($fil_get)){
                    $rs = $query->get($fil_get);
                }else{
                    $rs = $query->get();
                }
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_MODUL,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function addItem($data = array()){
        if (is_array($data)&&count($data)>0){
            try{
                DB::beginTransaction();
                $item = new self();
                foreach ($data as $k=>$v){
                    $item->$k=$v;
                }
                if ($item->save()){
                    if ($item->module_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->module_id);
                    }
                    return $item->module_id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function updateItem($data = array(),$id=0){
        if(is_array($data)&&count($data)>0&&$id>0){
            try{
                DB::beginTransaction();
                $item = self::find($id);
				$item->update($data);
                if($item!=null){
                    if(isset($item->module_id)&&$item->module_id>0){
                        self::removeCache($item->module_id);
                    }
                    DB::commit();
                    return true;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function getModuleAction($action){
        $result = self::where('module_controller', $action)->first();
        return $result;
    }
    public static function deleteItem($id=0){
        if ($id>0){
            try{
                DB::beginTransaction();
                $item = self::find($id);
                if ($item!=null){
                    $item->delete();
                    if (isset($item->module_id)&&$item->module_id>0){
                        self::removeCache($item->module_id);
                    }
                    DB::commit();
                    return true;
                }
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function saveItem($data=array(),$id=0){
        if ($id>0){
            return self::updateItem($data,$id);
        }else{
            return self::addItem($data);
        }
    }
}
