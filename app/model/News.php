<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class News extends Model
{
    protected $table = 'news';
    protected $primaryKey = 'news_id';
    public $timestamps  = false;
    const FOLDER = 'news';
    protected $fillable = array(
        'news_id',
        'news_title',
        'news_alias',
        'news_intro',
        'news_content',
        'news_media',
        'news_status',
		'news_key_parent',
        'news_created',
        'meta_title',
        'meta_keywords',
        'meta_description');
    public static function removeCache($id=0,$key=''){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_NEWS);
            Cache::forget(\Memcaches::CACHE_NEWS_ID.$id);
        }
        if($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_NEWS);
            Cache::forget(\Memcaches::CACHE_NEWS_KEY.$key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('news_id','>',0);
            if(isset($search['news_title'])&&$search['news_title']!=''){
                $query->where('news_title','like','%'.$search['news_title'].'%');
            }
			if(isset($search['news_key_parent'])&&$search['news_key_parent']!=-1){
                $query->where('news_key_parent',$search['news_key_parent']);
            }
            if(isset($search['news_status'])&&$search['news_status']!=-1){
                $query->where('news_status',$search['news_status']);
            }
            $total = $query->count();
            $query->orderby('news_created','desc');
            $fil_get = isset($search['fil_get'])&&$search['fil_get']!=''?explode(',',trim($search['fil_get'])):array();
            if (!empty($fil_get)){
                $rs = $query->take($limit)->skip($offset)->get($fil_get);
            }else{
                $rs = $query->take($limit)->skip($offset)->get();
            }
            return $rs;
        }catch (\PDOException $e){
            echo  $e;die;
            throw new \PDOException();
        }
    }
    public static function getIdByKeyword($key = '')
    {
        $id = 0;
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_NEWS_KEY . $key) : array();
        try {
            if (empty($rs)) {
                $rs = self::where('news_alias', $key)->first();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_NEWS_KEY . $key, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
        return !empty($rs)?$rs->news_id:$id;
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_NEWS_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_NEWS_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$cate='',$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_NEWS):array();
        if (empty($rs)){
            try{
                $query = self::where('news_id','>',0);
                $query->where('news_status',\CGlobal::status_show);
				if($cate!=''){
					$query->where('news_key_parent',$cate);
				}
                $query->orderby('news_created','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_NEWS,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
                    if ($item->news_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->news_id,$item->news_alias);
                    }
                    return $item->news_id;
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
                if(isset($item->news_id)&&$item->news_id>0){
                    self::removeCache($item->news_id,$item->news_alias);
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
                    if (isset($item->news_id)&&$item->news_id>0){
                        self::removeCache($item->news_id,$item->news_alias);
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
