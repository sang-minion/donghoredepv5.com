<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\PHPDev;

class Banner extends Model
{
    protected $table = 'banner';
    protected $primaryKey = 'banner_id';
    public $timestamps = false;
    const FOLDER = 'banner';
    protected $fillable = array(
        'banner_id',
        'banner_title',
        'banner_link',
        'banner_media',
        'banner_order_no',
		'banner_ghim',
        'banner_status',
        'banner_created');

    public static function removeCache($id = 0)
    {
        if ($id > 0) {
            Cache::forget(\Memcaches::CACHE_ALL_BANNER);
            Cache::forget(\Memcaches::CACHE_BANNER_ID. $id);
        }
    }

    public static function searchByCondition($search = array(), $limit = 0, $offset = 0, &$total = 0)
    {
        $rs = array();
        try {
            $query = self::where('banner_id', '>', 0);
            if (isset($search['banner_title']) && $search['banner_title'] != '') {
                $query->where('banner_title', 'like', '%' . $search['banner_title'] . '%');
            }
            if (isset($search['banner_status']) && $search['banner_status'] != -1) {
                $query->where('banner_status', $search['banner_status']);
            }
            $total = $query->count();
            $query->orderby('banner_order_no', 'asc');
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
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_BANNER_ID . $id) : array();
        if (empty($rs)) {
            try {
                $rs = self::find($id);
                if ($rs && \Memcaches::CACHE_ON) {
                    Cache::put(\Memcaches::CACHE_BANNER_ID . $id, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            } catch (\PDOException $e) {
                throw new \PDOException();
            }
        }
        return $rs;
    }

    public static function getAll($dataget = array(),$order_no, $limit = 0)
    {
        $rs = \Memcaches::CACHE_ON ? Cache::get(\Memcaches::CACHE_ALL_BANNER) : array();
        $rs  = array();
        if (empty($rs)) {
            try {
                $query = self::where('banner_id', '>', 0);
                $query->where('banner_status', \CGlobal::status_show);
                if($order_no!=-1){
                    $query->where('banner_order_no', $order_no);
                }
				$query->orderby('banner_ghim', 'desc');
                $query->orderby('banner_created', 'desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_BANNER, $rs, \Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            } catch (\PDOException $E) {
                throw new \PDOException();
            }
        }
        return $rs;
    }

    public static function addItem($data = array())
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
                    if ($item->banner_id && \Memcaches::CACHE_ON) {
                        self::removeCache($item->banner_id);
                    }
                    return $item->banner_id;
                }
                DB::commit();
            } catch (\PDOException $e) {
                DB::rollBack();
                echo $e;die;
                throw new \PDOException();
            }
        }
        return false;
    }

    public static function updateItem($data = array(), $id = 0)
    {
        if (is_array($data) && count($data) > 0 && $id > 0) {
            try {
                DB::beginTransaction();
                $item = self::find($id);
                $item->update($data);
                if (isset($item->banner_id) && $item->banner_id > 0) {
                    self::removeCache($item->banner_id);
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

    public static function deleteItem($id = 0)
    {
        if ($id > 0) {
            try {
                DB::beginTransaction();
                $item = self::find($id);
                if ($item != null) {
                    $item->delete();
                    DB::commit();
                    if (isset($item->banner_id) && $item->banner_id > 0) {
                        self::removeCache($item->banner_id);
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

    public static function saveItem($data = array(), $id = 0)
    {
        if ($id > 0) {
            return self::updateItem($data, $id);
        } else {
            return self::addItem($data);
        }
    }
}
