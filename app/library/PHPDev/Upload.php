<?php

/**
 * Created by PhpStorm.
 * User: Bui
 * Date: 30/05/2017
 * Time: 15:03 CH
 */
class Upload
{
    public static function uploadFile($_name = '', $_file_ext = '', $_max_file_size = '50*1024*1024', $_folder = '', $type_json = 1)
    {
        if ($_file_ext != '') {
            $_file_ext = explode(',', $_file_ext);
        } else {
            $_file_ext = array("jpg", "jpeg", "png", "gif");
        }
        if ($_name != '' && isset($_FILES[$_name]) && count($_FILES[$_name]) > 0) {
            if ($_max_file_size) {
                $max_file_size = $_max_file_size;
            } else {
                $max_file_size = 5 * 1024 * 1024;
            }
            $file_name = strtolower($_FILES[$_name]['name']);
            $file_tmp = $_FILES[$_name]["tmp_name"];
            $file_size = $_FILES[$_name]['size'];
            $file_type = $_FILES[$_name]['type'];
            $file_ext = @end(explode('.', $file_name));
            $ext = 0;
            $name = date('h-i-s-d-m-Y', time()) . '-' . self::pregReplaceString($file_name);
            $link = $name ? $name : '';
            if (!in_array($file_ext, $_file_ext)) {
                $ext = 0;
            } else {
                $ext = 1;
            }
            if ($file_name != '' && $ext == 1 && $file_size <= $max_file_size) {
                if ($_folder != '') {
                    $folder_upload = config('app.dir_root') . 'uploads/' . $_folder;
                } else {
                    $folder_upload = config('app.dir_root') . 'uploads/';
                }
                if (!is_dir($folder_upload)) {
                    @mkdir($folder_upload, 0777, true);
                    chmod($folder_upload, 0777);
                }
                if (move_uploaded_file($file_tmp, $folder_upload . '/' . $link)) {
                    $data = array('status' => 'Ok', 'src' => $link);
                } else {
                    $data = array('status' => 'Fail', 'src' => '');
                }

                if ($type_json) {
                    echo json_encode($data);
                    exit;
                } else {
                    return $link;
                }
            }
        }
    }
    // upload multi file
    public static function UploadMultiFile($_name = '', $_file_ext = '', $_max_file_size = '50*1024*1024', $_folder = '', $type_json = 1)
    {
        $arrImg = array();
        $data = array();
        if ($_max_file_size) {
            $max_file_size = $_max_file_size;
        } else {
            $max_file_size = 5 * 1024 * 1024;
        }
        if ($_file_ext != '') {
            $_file_ext = explode(',', $_file_ext);
        } else {
            $_file_ext = array("jpg", "jpeg", "png", "gif");
        }
        if ($_name != '' && isset($_FILES[$_name]) && count($_FILES[$_name]) > 0) {
            $c = count($_FILES[$_name]['name']);
            for ($i=0;$i<$c;$i++) {
                $file_name = strtolower($_FILES[$_name]['name'][$i]);
                $file_tmp = $_FILES[$_name]["tmp_name"][$i];
                $file_size = $_FILES[$_name]['size'][$i];
                $file_type = $_FILES[$_name]['type'][$i];
                $file_ext = @end(explode('.', $file_name));
                $ext = 0;
                $name = date('h-i-s-d-m-Y', time()) . '-' . self::pregReplaceString($file_name);
                $link = $name ? $name : '';
                if (!in_array($file_ext, $_file_ext)) {
                    $ext = 0;
                } else {
                    $ext = 1;
                }
                if ($file_name != '' && $ext == 1 && $file_size <= $max_file_size) {
                    if ($_folder != '') {
                        $folder_upload = config('app.dir_root') . 'uploads/' . $_folder;
                    } else {
                        $folder_upload = config('app.dir_root') . 'uploads/';
                    }
                    if (!is_dir($folder_upload)) {
                        @mkdir($folder_upload, 0777, true);
                        chmod($folder_upload, 0777);
                    }
                    if (move_uploaded_file($file_tmp, $folder_upload . '/' . $link)) {
                        $arrImg[] = $link;
                        $data[] = array('status' => 'Ok', 'src' => $link);
                    } else {
                        $data[] = array('status' => 'Fail', 'src' => '');
                    }
                }
            }
        }
        if ($type_json) {
            echo json_encode($data);
            exit;
        } else {
            return $arrImg;
        }
    }

    //Rename File Upload
    public static function pregReplaceString($str = '')
    {
        if (!$str) return '';
        if ($str != '') {
            $str = str_replace(array('^', '$', '\\', '/', '(', ')', '|', '?', '+', '_', '*', '[', ']', '{', '}', ',', '%', '<', '>', '=', '"', '“', '”', '!', ':', ';', '&', '~', '#', '`', "'", '@'), array(''), trim($str));

            $unicode = array(
                'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
                'd' => 'đ',
                'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
                'i' => 'í|ì|ỉ|ĩ|ị',
                'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
                'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
                'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
                'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
                'D' => 'Đ',
                'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
                'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
                'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
                'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
                'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
            );
            foreach ($unicode as $nonUnicode => $uni) {
                $str = preg_replace("/($uni)/i", $nonUnicode, $str);
            }

            $str = preg_replace("/\s+/", "-", $str);
            $str = preg_replace("/\-+/", "-", $str);

            return strtolower($str);
        }
    }

    // upload file

    public static function unlinkFileAndFolder($file_name = '', $id = 0, $folder = '', $folderThumbs = '', $is_delDir = 0)
    {
        if ($file_name != '') {
            //Remove Img In Database
            $paths = '';
            $pathThumbs = '';
            if ($folder != '' && $id > 0) {
                $path = config('app.dir_root') . '/' . $folder . '/' . $id;
            }
            if ($folderThumbs != '' && $id > 0) {
                $pathThumbs = config('app.dir_root') . '/' . $folderThumbs . '/' . $id;
            }
            if ($folder == '' && $folderThumbs == '') {
                $pathThumbs = config('app.dir_root') . '/uploads';
            }
            if ($file_name != '') {
                if ($path != '') {
                    if (is_file($path . '/' . $file_name)) {
                        @unlink($path . '/' . $file_name);
                    }
                }
                if ($pathThumbs != '') {
                    self::delete_dir_thumbs($pathThumbs, $file_name);
                }
            }
            //Remove Folder Empty
            if ($is_delDir) {
                if ($path != '') {
                    if (is_dir($path)) {
                        @rmdir($path);
                    }
                }
            }
        }
    }

    public static function delete_dir_thumbs($pathThumbs = '', $fileName = '')
    {
        if (is_dir($pathThumbs)) {
            $dir = opendir($pathThumbs);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($pathThumbs . '/' . $file)) {
                        self::delete_dir_thumbs($pathThumbs . '/' . $file, $fileName);
                    } else {
                        if ($file === $fileName) {
                            @unlink($pathThumbs . '/' . $file);
                        }
                    }
                }
            }
        closedir($dir);
    }

}
}