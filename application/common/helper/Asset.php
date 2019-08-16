<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\common\helper;

use think\File;
use think\Model;

class Asset extends Model {

    const STATUS_ONE = 1; // 可用
    const STATUS_ZROE = 0; // 不可用

    public function addData (File $file, $filePath) {
        $fileMd5 = $file->hash('md5');
        $fileSha1 = $file->hash('sha1');
        $fileKey = $fileMd5 . md5($fileSha1);

        $where = [
            'file_key' => $fileKey,
            'user_id' => get_current_admin_id() ? get_current_admin_id() : '',
        ];
        $fileExists = $this->checkFileExists($where);
        if (!$fileExists) {
            $data = [
                'user_id' => get_current_admin_id() ? get_current_admin_id() : '',
                'status' => self::STATUS_ONE,
                'file_size' => $file->getSize(),
                'file_key' => $fileKey,
                'filename' => $file->getInfo('name'),
                'file_path' => $filePath,
                'file_md5' => $fileMd5,
                'file_sha1' => $fileSha1,
                'suffix' => pathinfo($filePath)['extension'],
                'create_time' => time(),
            ];

            $info = self::create($data, true);

            return [
                'id' => $info['id'],
                'file_path' => $info['file_path'],
            ];
        } else {
            if (file_exists($fileExists['file_path'])) {
                return $fileExists;
            } else {
                return $this->upData($fileExists['id'], $filePath);
            }
        }
    }

    public function upData ($id, $filePath) {
        if ($id && file_exists($filePath)) {
            if ($this->where(['id' => $id])->setField('file_path', $filePath)) {
                return [
                    'id' => $id,
                    'file_path' => $filePath,
                ];
            }
            return false;
        }

        return false;
    }

    public function checkFileExists (array $where) {
        $info = $this->where($where)->find();
        if ($info) {
            return [
                'id' => $info['id'],
                'file_path' => $info['file_path'],
            ];
        }
        
        return $info;
    }

    public function getImgInfo ($ids) {
        if (is_string($ids) && $ids) {
            if (strpos($ids, ',')) {
                $ids = explode(',', $ids);
            } else {
                $ids = [$ids];
            }
        }
        if (!$ids) {
            return [];
        }

        $data = $this->whereIn('id', $ids)->column('id,filename,file_size,file_path', 'id');
        
        $result = [];
        foreach ($ids as $v) {
            $data[$v]['file_path'] = handle_image_url($data[$v]['file_path']);
            $result[] = $data[$v];
        }

        return $result;
    }
	

    private function tableDesc () {
        return '
            CREATE TABLE `asset` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `user_id` bigint(20) unsigned NOT NULL DEFAULT \'0\' COMMENT \'后台上传时会有，前台资源为空\',
              `file_size` bigint(20) unsigned NOT NULL DEFAULT \'0\' COMMENT \'文件大小,单位B\',
              `status` tinyint(3) unsigned NOT NULL DEFAULT \'1\' COMMENT \'状态;1:可用,0:不可用\',
              `download_times` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'下载次数\',
              `file_key` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT \'\' COMMENT \'文件惟一码\',
              `filename` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'\' COMMENT \'文件名\',
              `file_path` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT \'\' COMMENT \'文件路径,相对于upload目录,可以为url\',
              `file_md5` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT \'\' COMMENT \'文件md5值\',
              `file_sha1` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT \'\' COMMENT \'文件sha1值\',
              `suffix` varchar(10) NOT NULL DEFAULT \'\' COMMENT \'文件后缀名,不包括点\',
              `create_time` int(10) unsigned NOT NULL DEFAULT \'0\' COMMENT \'上传时间\',
              PRIMARY KEY (`id`),
              KEY `user_id` (`user_id`),
              KEY `file_key` (`file_key`) USING BTREE
            ) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COMMENT=\'资源存储参考表\';
        ';
    }
}