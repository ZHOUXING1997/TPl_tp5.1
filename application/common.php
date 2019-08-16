<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Request;
use \think\facade\Cache;

// 应用公共文件
/**
 * 获取网站根目录
 * @return string 网站根目录
 */
function cmf_get_root()
{
    $root    = Request::root();
    $root    = str_replace('/index.php', '', $root);
    if (defined('APP_NAMESPACE') && APP_NAMESPACE == 'api') {
        $root = preg_replace('/\/api$/', '', $root);
        $root = rtrim($root, '/');
    }

    return $root;
}

/**
 * 返回带协议的域名
 */
function cmf_get_domain()
{
    return Request::domain();
}

/**
 * 验证码检查，验证完后销毁验证码
 * @param string $value
 * @param string $id
 * @return bool
 */
function cmf_captcha_check($value, $id = "")
{
    $captcha = new \think\captcha\Captcha();
    return $captcha->check($value, $id);
}

/**
 * 获取当前登录的管理员ID
 * @return int
 */
function get_current_admin_id()
{
    return session('ADMIN_ID');
}

/**
 * 随机字符串生成
 * @param int $len 生成的字符串长度
 * @return string
 */
function common_random_string($len = 6)
{
    $chars    = [
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    ];
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
 * 获取客户端IP地址
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return string
 */
function get_client_ip($type = 0, $adv = true, $isToLong = false)
{
    $ip = request::ip($type, $adv);
    return $isToLong ? ip2long($ip) : $ip;
}

// 加密
function encryptQues($data) {
    $json = json_encode($data);
    $str = openssl_encrypt($json, "AES-128-CBC", config('aes_ak'), OPENSSL_RAW_DATA, config('aes_sk'));
    return base64_encode($str);
}
// 解密
function decryptQues($encryptedRaw) {
    $str = base64_decode($encryptedRaw);
    $json = openssl_decrypt($str, "AES-128-CBC", config('aes_ak'), OPENSSL_RAW_DATA, config('aes_sk'));
    $data = json_decode($json, true);
    return $data;
}

// 查询资源表资源
/**
 * @param $id
 * @return array|mixed|string
 */
function get_resources_by_id ($id, $isResetKey = true) {
    if (is_array($id) && empty($id)) {
        return [];
    } elseif (!$id) {
        return '';
    } elseif (is_string($id)) {
        if (strpos($id, ',')) {
            $id = explode(',', $id);
        }
    } else {
        return '';
    }
    
    if (is_array($id)) {
        $paths = [];
        foreach ($id as $idk => $idv) {
            $cacheKey = 'asset_id_' . $idv;
            if (Cache::has($cacheKey)) {
                $paths[$idv] = Cache::get($cacheKey);
                continue;
            }
            $path = app('AssetModel')->checkFileExists(['id' => $idv])['file_path'];
            $paths[$idv] = $path = handle_image_url($path);
            Cache::set($cacheKey, $path);
        }
        if ($isResetKey) {
            $paths= array_values($paths);
        }
        return $paths;
    } else {
        $cacheKey = 'asset_id_' . $id;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }
        $path = app('AssetModel')->checkFileExists(['id' => $id])['file_path'];
        $path = handle_image_url($path);
        Cache::set($cacheKey, $path);
        return $path;
    }
}

// 处理文件成可以访问
function handle_image_url ($file) {
    if (strpos($file, "http") === 0 || strpos($file, "https") === 0) {
        return $file;
    } else if (strpos($file, "/") === 0) {
        return cmf_get_domain() . $file;
    } else {
        $url = cmf_get_domain() . cmf_get_root() . '/' . $file;
        $url = str_replace('\\', '/', $url);
        
        return $url;
    }
}

function is_local() {
    $httpHost = get_client_ip(0, true);
    $localIp = [
        '0.0.0.0',
        'localhost',
        '127.0.0.1',
    ];
    if (in_array(strtolower($httpHost), $localIp)) {
        return true;
    }

    $toks = explode('.', $httpHost);
    if ($toks[0] == '127' || $toks[0] == '10' || $toks[0] == '192') {
        return true;
    }

    return false;
}

function ipToLong ($ip) {
    $long = ip2long($ip);
    if ($long < 1) {
        // return sprintf("%u\n", ip2long($ip));
        return sprintf("%u", ip2long($ip));
    }
    return $long;
}