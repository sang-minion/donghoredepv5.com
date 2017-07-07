<?php
/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 28/06/2017
 * Time: 9:16 SA
 */

namespace App\model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CommentHome extends Model
{
    protected $table = 'commenthome';
    protected $primaryKey = 'cmt_id';
    public $timestamps  = false;
    const FOLDER = 'commenthome';
    protected $fillable = array(
        'cmt_id',
        'cmt_name',
        'cmt_link',
        'cmt_avt',
        'cmt_content',
        'cmt_status',
        'cmt_created');

    public static function removeCache($id=0,$key=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_COMMENTADMIN);
            Cache::forget(\Memcaches::CACHE_COMMENTADMIN_ID.$id);
        }
        if($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_COMMENTADMIN);
            Cache::forget(\Memcaches::CACHE_ALL_COMMENTADMIN_KEY.$key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('cmt_id','>',0);
            if(isset($search['name'])&&$search['name']!=''){
                $query->where('cmt_name','like','%'.$search['name'].'%');
            }
            if(isset($search['cmt_status'])&&$search['cmt_status']!=-1){
                $query->where('cmt_status',$search['cmt_status']);
            }
            $total = $query->count();
            $query->orderby('cmt_created','desc');
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
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_COMMENTADMIN_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_COMMENTADMIN_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public  static  function  getCommentByName($name){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_COMMENTADMIN_KEY.$name):array();
        if (empty($rs)){
            try{
                $query = self::where('cmt_id','>',0);
                $query->where('cmt_name',$name);
                $rs = $query->get();
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_ALL_COMMENTADMIN_KEY.$name,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_COMMENTADMIN):array();
        if (empty($rs)){
            try{
                $query = self::where('cmt_id','>',0);
                $query->where('cmt_status',\CGlobal::status_show);
                $query->orderby('cmt_created','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_COMMENTADMIN,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    if ($item->cmt_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->cmt_id,$item->cmt_name);
                    }
                    return $item->cmt_id;
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
                if(isset($item->cmt_id)&&$item->cmt_id>0){
                    self::removeCache($item->cmt_id,$item->cmt_name);
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
                    if (isset($item->cmt_id)&&$item->cmt_id>0){
                        self::removeCache($item->cmt_id,$item->cmt_name);
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