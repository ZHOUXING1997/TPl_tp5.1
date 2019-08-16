<?php
/**
 * User: 周星
 * Date: 2019/4/17
 * Time: 12:05
 * Email: zhouxing@benbang.com
 */

namespace app\open\controller;

use util\ReqResp;
use util\Upload;

class UpFile {

    public function upload () {
        try {
            $files = request()->file('file');
            if (!is_array($files)) {
                $files = [$files];
            }
            $result = [];
            foreach ($files as $file) {
                $fileMd5 = $file->hash('md5');
                $fileSha1 = $file->hash('sha1');
                $fileKey = $fileMd5 . md5($fileSha1);
                $where = [
                    'file_key' => $fileKey,
                    'user_id' => get_current_admin_id() ? get_current_admin_id() : '',
                ];
                $res = app('AssetModel')->checkFileExists($where);
                if (!$res) {
                    $path = Upload::uploadImg($file);
                    $res = app('AssetModel')->addData($file, $path);
                } else {
                    if (file_exists($res['file_path'])) {
                        $res['file_path'] = handle_image_url($res['file_path']);
                    } else {
                        $path = Upload::uploadImg($file);
                        $res = app('AssetModel')->upData($res['id'], $path);
                    }
                }
                $result[] = $res;
            }

            ReqResp::outputSuccess(count($result) < 2 ? reset($result) : $result, '上传成功');
        } catch (\Exception $e) {
            ReqResp::outputFail($e);
        }
    }
}