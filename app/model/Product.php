<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'product_id';
    public $timestamps  = false;
    const FOLDER = 'product';
    protected $fillable = array(
        'product_id',
        'product_code',
        'product_alias',
        'product_cate_id',
        'product_brand_id',
        'product_title',
        'product_price_input',
        'product_price',
        'product_price_multi',
        'product_price_saleof',
        'product_intro',
        'product_why',
        'product_details',
        'product_order_no',
        'product_media',
        'product_multi_media',
        'product_video',
        'product_vote',
        'product_status',
        'product_cheapest',
        'product_color',
		'product_gift_code',
        'product_most',
        'product_news',
        'product_buy_most',
        'product_best',
        'product_created',
        'meta_title',
        'meta_keywords',
        'meta_description');
    public static function removeCache($id=0,$key=''){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_PRODUCT);
            Cache::forget(\Memcaches::CACHE_PRODUCT_ID.$id);
        }
        if ($key!=''){
			Cache::forget(\Memcaches::CACHE_ALL_PRODUCT);
            Cache::forget(\Memcaches::CACHE_PRODUCT_KEY . $key);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('product_id','>',0);
            if(isset($search['product_title'])&&$search['product_title']!=''){
                $query->where('product_title','like','%'.$search['product_title'].'%');
            }
            if(isset($search['product_code'])&&$search['product_code']!=''){
                $query->where('product_code','like','%'.$search['product_code'].'%');
            }
            if(isset($search['product_cate_id'])&&$search['product_cate_id']!=-1){
                $query->where('product_cate_id',$search['product_cate_id']);
            }
            if(isset($search['product_brand_id'])&&$search['product_brand_id']!=-1){
                $query->where('product_brand_id',$search['product_brand_id']);
            }
            if(isset($search['product_cheapest'])&&$search['product_cheapest']!=-1){
                $query->where('product_cheapest',$search['product_cheapest']);
            }
            if(isset($search['product_buy_most'])&&$search['product_buy_most']!=-1){
                $query->where('product_buy_most',$search['product_buy_most']);
            }
            if(isset($search['product_most'])&&$search['product_most']!=-1){
                $query->where('product_most',$search['product_most']);
            }
            if(isset($search['product_most'])&&$search['product_most']!=-1){
                $query->where('product_most',$search['product_most']);
            }
            if(isset($search['product_news'])&&$search['product_news']!=-1){
                $query->where('product_news',$search['product_news']);
            }
            if(isset($search['product_best'])&&$search['product_best']!=-1){
                $query->where('product_best',$search['product_best']);
            }
            if(isset($search['product_saleof'])&&$search['product_saleof']!=-1){
                $query->where('product_price_saleof','>',0);
            }
            if(isset($search['product_status'])&&$search['product_status']!=-1){
                $query->where('product_status',$search['product_status']);
            }
            $total = $query->count();
            if(isset($search['orderby'])&&!empty($search['orderby'])){
                $query->orderby($search['orderby'][0],$search['orderby'][1]);
            }else{
                $query->orderby('product_id','desc');
            }
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
    public static function getIdByKeyword($key = '')
    {
        $id = 0;
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_PRODUCT_KEY . $key) : array();
        try {
            if (empty($rs)) {
                $rs = self::where('product_alias', $key)->first();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_PRODUCT_KEY . $key, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
        return !empty($rs)?$rs->product_id:$id;
    }
    public static function getById($id=0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_PRODUCT_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs && \Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_PRODUCT_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_PRODUCT):array();
        if (empty($rs)){
            try{
                $query = self::where('product_id','>',0);
                $query->where('product_status',\CGlobal::status_show);
                $query->orderby('product_order_no','asc');
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
                    Cache::put(\Memcaches::CACHE_ALL_PRODUCT,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getOrderCart($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('product_id','>',0);
            if(isset($search['product_id'])&&is_array($search['product_id'])){
                $query->whereIn('product_id',$search['product_id']);
            }
            if(isset($search['product_status'])&&$search['product_status']!=-1){
                $query->where('product_status',$search['product_status']);
            }
            $total = $query->count();
            $query->orderby('product_id','asc');
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
	public static function getAllGift(){
        $rs = array();
            try{
                $query = self::where('product_id','>',0);
                $query->where('product_status',\CGlobal::status_show);
				$query->where('product_gif',\CGlobal::status_show);
                $query->orderby('product_id','asc');                
                    $rs = $query->get();
            }catch (\PDOException $E){
                throw new \PDOException();
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
                    if ($item->product_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->product_id,$item->product_alias);
                    }
                    return $item->product_id;
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
                if(isset($item->product_id)&&$item->product_id>0){
                    self::removeCache($item->product_id,$item->product_alias);
                }
                DB::commit();
                return true;
            }catch (\PDOException $e){
                DB::rollBack();
				var_dump($e);
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
                    if (isset($item->product_id)&&$item->product_id>0){
                        self::removeCache($item->product_id,$item->product_alias);
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
