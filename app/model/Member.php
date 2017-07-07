<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 01/07/2017
 * Time: 14:39 CH
 */

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    protected $table = 'member';
    protected $primaryKey = 'member_id';
    public $timestamps  = false;
    const FOLDER = 'member';
    protected $fillable = array(
        'member_id',
        'member_name',
        'member_phone',
        'member_email',
        'member_address',
        'member_avt',
        'member_age',
        'member_status',
        'member_created',
        'member_pass',
        'remember_token',
        'member_last_login',
        'member_last_ip');

    public static function removeCache($id=0,$key=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_MEMBER);
            Cache::forget(\Memcaches::CACHE_MEMBER_ID.$id);
        }
        if($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_MEMBER);
            Cache::forget(\Memcaches::CACHE_ALL_MEMBER_KEY.$key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('member_id','>',0);
            if(isset($search['name'])&&$search['name']!=''){
                $query->where('member_name','like','%'.$search['name'].'%');
                $query->orWhere('member_phone','like','%'.$search['name'].'%');
                $query->orWhere('member_email','like','%'.$search['name'].'%');
            }
            if(isset($search['member_status'])&&$search['member_status']!=-1){
                $query->where('member_status',$search['member_status']);
            }
            $total = $query->count();
            $query->orderby('member_created','desc');
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
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_MEMBER_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_MEMBER_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public  static  function  getLogin($mail){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_MEMBER_KEY.$mail):array();
        if (empty($rs)){
            try{
                $query = self::where('member_id','>',0);
                $query->where('member_email',$mail);
//                $query->where('member_status',\CGlobal::status_show);
                $rs = $query->get();
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_MEMBER_KEY.$mail,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_MEMBER):array();
        if (empty($rs)){
            try{
                $query = self::where('member_id','>',0);
                $query->where('member_status',\CGlobal::status_show);
                $query->orderby('member_created','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_MEMBER,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    DB::commit();
                    if ($item->member_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->member_id,$item->member_email);
                    }
                    return $item->member_id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                echo $e;die;
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
                if(isset($item->member_id)&&$item->member_id>0){
                    self::removeCache($item->member_id,$item->member_email);
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
                    if (isset($item->member_id)&&$item->member_id>0){
                        self::removeCache($item->member_id,$item->member_email);
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