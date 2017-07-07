<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Trash extends Model
{
    protected $table = 'trash';
    protected $primaryKey = 'trash_id';
    public $timestamps  = false;
    const FOLDER = 'trash';
    protected $fillable = array('trash_id','user_id','trash_obj_id','trash_title','trash_class','trash_content','trash_one_media',
        'trash_multi_media','trash_folder_media','trash_created');

    public static function removeCache($id=0){
        if ($id>0){
            Cache::forget(\Memcaches::CACHE_ALL_TRASH);
            Cache::forget(\Memcaches::CACHE_TRASH_ID.$id);
        }
    }
    public static function searchByCondition($search = array(),$limit=0,$offset=0,&$total=0){
        $rs = array();
        try{
            $query = self::where('trash_id','>',0);
            if(isset($search['trash_title'])&&$search['trash_title']!=''){
                $query->where('trash_title','like','%'.$search['trash_title'].'%');
            }
            $total = $query->count();
            $query->orderby('trash_id','desc');
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
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_TRASH_ID.$id):array();
        if (empty($rs)){
            try{
                $rs = self::find($id);
                if ($rs&&\Memcaches::CACHE_ON){
                    Cache::put(\Memcaches::CACHE_TRASH_ID.$id,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $e){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function getAll($dataget = array(),$limit = 0){
        $rs = \Memcaches::CACHE_ON?Cache::get(\Memcaches::CACHE_ALL_TRASH):array();
        if (empty($rs)){
            try{
                $query = self::where('trash_id','>',0);
                $query->orderby('trash_id','desc');
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
                    Cache::put(\Memcaches::CACHE_ALL_TRASH,$rs,\Memcaches::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }catch (\PDOException $E){
                throw new \PDOException();
            }
        }
        return $rs;
    }
    public static function addData($data = array()){
        if (is_array($data)&&count($data)>0){
            try{
                DB::beginTransaction();
                $item = new self();
                foreach ($data as $k=>$v){
                    $item->$k=$v;
                }
                if ($item->save()){
                    DB::commit();
                    if ($item->trash_id&&\Memcaches::CACHE_ON){
                        self::removeCache($item->trash_id);
                    }
                    return $item->trash_id;
                }
                DB::commit();
                return false;
            }catch (\PDOException $e){
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function addItem($id=0,$user_id=0, $class='', $folder='', $field_id, $field_title='', $field_one_media='', $field_multi_media=''){
        if($id > 0){
            if(class_exists($class)){
                $result = $class::where($field_id, $id)->first();
                $imgMain = '';
                $multiMedia = array();
                $ObjClass = new $class();
                $arrField = $ObjClass->getFillable();
                if($result != null){
                    if($folder != ''){
                        $folder_trash = config('app.dir_root').'uploads/'.self::FOLDER.'/'.$folder.'/'.$id;
                        if(!is_dir($folder_trash)){
                            @mkdir($folder_trash,0777,true);
                            chmod($folder_trash,0777);
                        }

                        if($field_multi_media != ''){
                            $strMultiMedia = $result->$field_multi_media;
                            if($strMultiMedia != ''){
                                $arrMultiMedia = unserialize($strMultiMedia);
                                if(is_array($arrMultiMedia) && !empty($arrMultiMedia)){
                                    foreach($arrMultiMedia as $media){
                                        $file_current = config('app.dir_root').'uploads/'.$folder.'/'.$id.'/'.$media;
                                        $file_trash = $folder_trash.'/'.$media;
                                        if(is_file($file_current)){
                                            copy($file_current, $file_trash);
                                            $multiMedia[] = $media;
                                            unlink($file_current);
                                        }
                                    }
                                }
                            }
                            if($field_one_media != ''){
                                $imgMain = $result->$field_one_media;
                            }

                        }
                        if($field_one_media != ''){
                            $file_current = config('app.dir_root').'uploads/'.$folder.'/'.$id.'/'.$result->$field_one_media;
                            $file_trash = $folder_trash.'/'.$result->$field_one_media;
                            if(is_file($file_current)){
                                copy($file_current, $file_trash);
                                $imgMain = $result->$field_one_media;
                                unlink($file_current);
                            }
                        }
                    }

                    $title = '';
                    if($field_title != ''){
                        if(isset($result->$field_title)){
                            $title = $result->$field_title;
                        }
                    }

                    $data = array();
                    foreach($arrField as $field){
                        $data[$field] = $result->$field;
                    }

                    $arrContent = $data;
                    $data = array(
                        'user_id'=>$user_id,
                        'trash_obj_id'=>$id,
                        'trash_title'=>$title,
                        'trash_class' => $class,
                        'trash_content'=>serialize($arrContent),
                        'trash_one_media'=>$imgMain,
                        'trash_multi_media'=>serialize($multiMedia),
                        'trash_folder_media'=>$folder,
                        'trash_created'=>time(),
                    );
                    self::addData($data);
                }
            }
        }
        return true;
    }
    public static function restoreItem($id=0){
        if($id > 0){
            $data = Trash::getById($id);
            if($data != null){
                $class = $data->trash_class;
                $trash_one_media = $data->trash_one_media;
                $trash_multi_media = $data->trash_multi_media;
                $dataRetore = unserialize($data->trash_content);

                $arrMultiMedia = array();
                if($trash_multi_media != ''){
                    $arrMultiMedia  = unserialize($trash_multi_media);
                }

                $folder_current = config('app.dir_root').'uploads/'.$data->trash_folder_media.'/'.$data->trash_obj_id;

                if($trash_one_media != '' || $trash_multi_media != ''){
                    if(!is_dir($folder_current)){
                        @mkdir($folder_current,0777,true);
                        chmod($folder_current,0777);
                    }
                }

                if(is_array($arrMultiMedia) && !empty($arrMultiMedia)){
                    foreach($arrMultiMedia as $media){
                        $file_recyclebin = config('app.dir_root').'uploads/'.self::FOLDER.'/'.$data->trash_folder_media.'/'.$data->trash_obj_id.'/'.$media;
                        $file_current = $folder_current.'/'.$media;
                        if(is_file($file_recyclebin)){
                            copy($file_recyclebin, $file_current);
                            unlink($file_recyclebin);
                        }
                    }
                }
                if($trash_one_media != ''){
                    $file_recyclebin = config('app.dir_root').'uploads/'.self::FOLDER.'/'.$data->trash_folder_media.'/'.$data->trash_obj_id.'/'.$trash_one_media;
                    $file_current = $folder_current.'/'.$trash_one_media;
                    if(is_file($file_recyclebin)){
                        copy($file_recyclebin, $file_current);
                        unlink($file_recyclebin);
                    }
                }
//                echo  $class; var_dump(class_exists($class));
//                var_dump($dataRetore);die;
                if(!empty($dataRetore)){
                    $class::addItem($dataRetore);
                }
            }
        }
        return true;
    }
    public static function updateItem($data = array(),$id=0){

    }
    public static function deleteItem($id=0){
        if ($id>0){
            try {
                DB::beginTransaction();
                $data = Trash::find($id);
                if($data != null){
                    //Remove Img
                    $trash_multi_media = ($data->trash_multi_media != '') ? unserialize($data->trash_multi_media) : array();
                    if(is_array($trash_multi_media) && !empty($trash_multi_media)){
                        $path = config('app.dir_root').'uploads/'.self::FOLDER.'/'.$data->trash_folder_media.'/'.$data->trash_obj_id;

                        foreach($trash_multi_media as $v){
                            if(is_file($path.'/'.$v)){
                                @unlink($path.'/'.$v);
                            }
                        }
                        if(is_dir($path)) {
                            @rmdir($path);
                        }
                    }
                    if($data->trash_one_media != ''){
                        $path = config('app.dir_root').'uploads/'.self::FOLDER.'/'.$data->trash_folder_media.'/'.$data->trash_obj_id;
                        if(is_file($path.'/'.$data->trash_one_media)){
                            @unlink($path.'/'.$data->trash_one_media);
                        }
                        if(is_dir($path)) {
                            @rmdir($path);
                        }
                    }
                    //End Remove Img
                    $data->delete();
                    if(isset($data->trash_id) && $data->trash_id > 0){
                        self::removeCache($data->trash_id);
                    }
                    DB::commit();
                }
                return true;
            } catch (\PDOException $e) {
                DB::rollBack();
                throw new \PDOException();
            }
        }
        return false;
    }
    public static function saveItem($data=array(),$id=0){

    }
}
