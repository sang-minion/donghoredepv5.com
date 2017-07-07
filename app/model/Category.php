<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    const FOLDER = 'category';
    protected $fillable = array(
        'category_id',
        'category_parent_id',
        'category_keyword',
        'category_title',
        'category_intro',
        'category_media',
        'category_media_banner',
        'category_status',
        'horizontal_menu',
        'vertical_menu',
        'category_order_no',
        'category_created',
        'meta_title',
        'meta_keywords',
        'meta_description');

    public static function removeCache($id = 0,$key='')
    {
        if ($id > 0) {
            Cache::forget(\Memcaches::CACHE_ALL_CATEGORY);
            Cache::forget(\Memcaches::CACHE_CATEGORY_ID . $id);
            Cache::forget(\Memcaches::CACHE_ALL_CATEGORY_MENU);
        }
        if ($key!=''){
            Cache::forget(\Memcaches::CACHE_CATEGORY_key . $key);
			Cache::forget(\Memcaches::CACHE_ALL_CATEGORY);
			Cache::forget(\Memcaches::CACHE_ALL_CATEGORY_MENU);
        }
    }

    public static function searchByCondition($search = array(), $limit = 0, $offset = 0, &$total = 0)
    {
        $rs = array();
        try {
            $query = self::where('category_id', '>', 0);
            if (isset($search['category_title']) && $search['category_title'] != '') {
                $query->where('category_title', 'like', '%' . $search['category_title'] . '%');
            }
            if (isset($search['category_parent_id']) && $search['category_parent_id'] != -1) {
                $query->where('category_parent_id', $search['category_parent_id']);
            }
            if (isset($search['category_status']) && $search['category_status'] != -1) {
                $query->where('category_status', $search['category_status']);
            }
            if (isset($search['horizontal_menu']) && $search['horizontal_menu'] != -1) {
                $query->where('horizontal_menu', $search['horizontal_menu']);
            }
            if (isset($search['vertical_menu']) && $search['vertical_menu'] != -1) {
                $query->where('vertical_menu', $search['vertical_menu']);
            }
            $total = $query->count();
            $query->orderby('category_order_no', 'asc');
            $fil_get = isset($search['fil_get']) && $search['fil_get'] != '' ? explode(',', trim($search['fil_get'])) : array();
            if (!empty($fil_get)) {
                $rs = $query->take($limit)->skip($offset)->get($fil_get);
            } else {
                $rs = $query->take($limit)->skip($offset)->get();
            }
            return $rs;
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
    }

    public static function getById($id = 0)
    {
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_CATEGORY_ID . $id) : array();
        if (empty($rs)) {
            try {
                $rs = self::find($id);
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_CATEGORY_ID . $id, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            } catch (\PDOException $e) {
                throw new \PDOException();
            }
        }
        return $rs;
    }

    public static function getIdByKeyword($key = '')
    {
        $id = 0;
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_CATEGORY_key . $key) : array();
        try {
            if (empty($rs)) {
                $rs = self::where('category_keyword', $key)->first();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_CATEGORY_key . $key, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
        return !empty($rs)?$rs->category_id:$id;
    }

    public static function getAll($dataget = array(), $limit = 0)
    {
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_ALL_CATEGORY) : array();
        $rs = array();
        if (empty($rs)) {
            try {
                $query = self::where('category_id', '>', 0);
                if (isset($dataget['category_parent_id']) && $dataget['category_parent_id'] != -1) {
                    $query->where('category_parent_id', $dataget['category_parent_id']);
                }
                if (isset($dataget['horizontal_menu']) && $dataget['horizontal_menu'] != -1) {
                    $query->where('horizontal_menu', $dataget['horizontal_menu']);
                }
                if (isset($dataget['vertical_menu']) && $dataget['vertical_menu'] != -1) {
                    $query->where('vertical_menu', $dataget['vertical_menu']);
                }
                $query->where('category_status', \CGlobal::status_show);
                $query->orderby('category_order_no', 'asc');
                $fil_get = isset($dataget['fil_get']) && $dataget['fil_get'] ? explode(',', trim($dataget['fil_get'])) : array();
                if ($limit > 0) {
                    $query->take($limit);
                }
                if (!empty($fil_get)) {
                    $rs = $query->get($fil_get);
                } else {
                    $rs = $query->get();
                }
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_ALL_CATEGORY, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            } catch (\PDOException $E) {
                throw new \PDOException();
            }
        }
        return $rs;
    }

    public static function getAllMenu()
    {
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_ALL_CATEGORY_MENU) : array();
        $rs = array();
        if (empty($rs)) {
            try {
                $query = self::where('category_id', '>', 0);
                $query->where('category_status', \CGlobal::status_show);
                $query->where('horizontal_menu', \CGlobal::status_show);
                $query->orWhere('vertical_menu', \CGlobal::status_show);
                $query->orderby('category_order_no', 'asc');
                $rs = $query->get();
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_ALL_CATEGORY_MENU, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            } catch (\PDOException $E) {
                throw new \PDOException();
            }
        }
        return $rs;
    }

    public
    static function addItem($data = array())
    {
        if (is_array($data) && count($data) > 0) {
            try {
                DB::beginTransaction();
                $item = new self();
                foreach ($data as $k => $v) {
                    $item->$k = $v;
                }
                if ($item->save()) {
                    DB::commit();
                    if ($item->category_id && \Memcaches::CACHE_ON) {
                        self::removeCache($item->category_id,$item->category_keyword);
                    }
                    return $item->category_id;
                }
                DB::commit();
            } catch (\PDOException $e) {
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }

    public
    static function updateItem($data = array(), $id = 0)
    {
        if (is_array($data) && count($data) > 0 && $id > 0) {
            try {
                DB::beginTransaction();
                $item = self::find($id);
                $item->update($data);
                if (isset($item->category_id) && $item->category_id > 0) {
                    self::removeCache($item->category_id,$item->category_keyword);
                }
                DB::commit();
                return true;
            } catch (\PDOException $e) {
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }

    public
    static function deleteItem($id = 0)
    {
        if ($id > 0) {
            try {
                DB::beginTransaction();
                $item = self::find($id);
                if ($item != null) {
                    $item->delete();
                    DB::commit();
                    if (isset($item->category_id) && $item->category_id > 0) {
                        self::removeCache($item->category_id,$item->category_keyword);
                    }
                    return true;
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }

    public
    static function saveItem($data = array(), $id = 0)
    {
        if ($id > 0) {
            return self::updateItem($data, $id);
        } else {
            return self::addItem($data);
        }
    }
}
