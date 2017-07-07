<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps  = true;
    const FOLDER = 'users';
    protected $fillable = array(
        'id',
        'user_role_id',
        'name',
        'user_phone',
        'user_address',
        'user_last_login',
        'user_last_ip',
        'user_status',
        'email',
        'password',
        'remember_token',
        'created_at',
        'updated_at');

    public static function removeCache($id=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_USER);
            Cache::forget(\Memcaches::CACHE_USER_ID.$id);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('id','>',0);
            if(isset($search['user_role_id'])&&$search['user_role_id']!=-1){
                $query->where('user_role_id',$search['user_role_id']);
            }
            if(isset($search['name'])&&$search['name']!=''){
                $query->where('name','like','%'.$search['name'].'%');
            }
            if(isset($search['user_status'])&&$search['user_status']!=-1){
                $query->where('user_status',$search['user_status']);
            }
            $total = $query->count();
            $query->orderby('created_at','desc');
            $fil_get = isset($search['fil_get'])&&$search['fil_get']!=''?explode(',',trim($search['fil_get'])):array();
            if (!empty($fil_get)){
                $rs = $query->take($limit)->skip($offset)->get($fil_get);
            }else{
                $rs = $query->take($limit)->skip($offset)->get();
            }
            return $rs;
        }catch (\PDOException $e){
            echo $e;die;
            throw new \PDOException();
        }
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_USER_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_USER_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }

    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_USER):array();
        if (empty($rs)){
            try{
                $query = self::where('id','>',0);
                $query->where('user_status',\CGlobal::status_show);
                $query->orderby('created_at','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_USER,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    DB::connection()->getPdo()->commit();
                    if ($item->id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->id);
                    }
                    return $item->id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                echo  $e;die;
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
                if(isset($item->id)&&$item->id>0){
                    self::removeCache($item->id);
                }
                DB::commit();
                return true;
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function deleteItem($id=0){
        if ($id>0){
            try{
                DB::beginTransaction();
                $item = self::find($id);
                if ($item!=null){
                    $item->delete();
                    DB::commit();
                    if (isset($item->id)&&$item->id>0){
                        self::removeCache($item->id);
                    }
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
