<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 10/07/2017
 * Time: 17:12 CH
 */

namespace App\model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VoteProduct extends Model
{
    protected $table='voteproduct';
    protected $primaryKey='vote_id';
    public $timestamps = false;
    const FOLDER = 'voteproduct';
    protected $fillable = array(
        'vote_id',
        'vote_ip_address',
        'vote_product_id',
        'vote_num',
        'vote_created',
        'vote_uid');

    public static function removeCache($id=0,$ip=''){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_VOTE);
            Cache::forget(\Memcaches::CACHE_VOTE_ID.$id);
        }
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_VOTE);
            Cache::forget(\Memcaches::CACHE_VOTE_IP.$id);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('vote_id','>',0);
            if(isset($search['vote_product_id'])&&$search['vote_product_id']!=-1){
                $query->where('vote_product_id',$search['vote_product_id']);
            }
            if(isset($search['vote_num'])&&$search['vote_num']!=-1){
                $query->where('vote_num',$search['vote_num']);
            }
            $total = $query->count();
            $query->orderby('vote_id','desc');
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
    public static function getByIdProduct($productid=0,$num=0){
        $rs = array();
            try{
                $query = self::where('vote_id','>',0);
                if($productid!=-1){
                    $query->where('vote_product_id',$productid);
                }
                if($num>0){
                    $query->where('vote_num',$num);
                }
                $rs = $query->get();
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        return count($rs);
    }
    public static function checkVote($productid=0,$ip='',$uid=0){
        $rs = array();
            try{
                $query = self::where('vote_id','>',0);
                if($productid!=-1){
                    $query->where('vote_product_id',$productid);
                }
                if($uid!=-1){
                    $query->where('vote_uid',$uid);
                }
                if($ip!=''){
                    $query->where('vote_ip_address',$ip);
                }
                $rs = $query->get();
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        return $rs;
    }
    public static function getIdByKeyword($key = '')
    {
        $id = 0;
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_VOTE_IP . $key) : array();
        try {
            if (empty($rs)) {
                $rs = self::where('vote_ip_address', $key)->first();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_VOTE_IP . $key, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
        return !empty($rs)?$rs->product_id:$id;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_VOTE):array();
        if (empty($rs)){
            try{
                $query = self::where('vote_id','>',0);
                $query->orderby('vote_id','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_VOTE,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    if ($item->vote_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->vote_id,$item->vote_ip_address);
                    }
                    DB::commit();
                    return $item->vote_id;
                }
                DB::commit();
            }catch (\PDOException $e){
                DB::rollBack();
                var_dump($e);die;
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
                if(isset($item->vote_id)&&$item->vote_id>0){
                    self::removeCache($item->vote_id,$item->vote_ip_address);
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
                    if (isset($item->vote_id)&&$item->vote_id>0){
                        self::removeCache($item->vote_id,$item->vote_ip_address);
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