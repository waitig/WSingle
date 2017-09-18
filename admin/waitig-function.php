<?php
/**
 * File: waitig-function.php.
 * User: admin
 * Date: 2017/9/18
 * Time: 15:41
 * Index:https://www.waitig.com
 */

define('THEME_CHECK_KEY', 'waitig-theme-check');
define('THEME_CHECK_URL', 'http://127.0.0.1/WBetter/check/theme/wsingle.php');
function passport_encrypt($str, $key)
{
    srand((double)microtime() * 1000000);
    $encrypt_key = md5(rand(0, 32000));
    $ctr = 0;
    $tmp = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $encrypt_key[$ctr] . ($str[$i] ^ $encrypt_key[$ctr++]);
    }
    return base64_encode(passport_key($tmp, $key));
}

function passport_key($str, $encrypt_key)
{
    $encrypt_key = md5($encrypt_key);
    $ctr = 0;
    $tmp = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $str[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}

function passport_decrypt($str, $key)
{
    $str = passport_key(base64_decode($str), $key);
    $tmp = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $md5 = $str[$i];
        $tmp .= $str[++$i] ^ $md5;
    }
    return $tmp;
}

/**
 * 发送post请求
 * @param string $url 请求地址
 * @param array $post_data post键值对数据
 * @return string
 */
function send_post($url, $post_data)
{
    $postdata = http_build_query($post_data);
    //'Content-type:application/x-www-form-urlencoded\r\n'.
    $header = //'Content-type:application/x-www-form-urlencoded;charset=utf-8\r\n'.
        'User-Agent:' . constant('THEME_CHECK_KEY');
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => $header,
            'content' => $postdata,
            'timeout' => 15 * 60 // 超时时间（单位:s）
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}

/**
 * 检查主题是否过期的函数
 * @return string
 */
function theme_check()
{
    global $themename;
    $url = get_bloginfo('url');
    $themeData = array(
        'url' => $url,
        'theme' => $themename
    );

//    if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
//        return "欢迎使用 $themename 主题 !";
//    }
    $requestUrl = constant('THEME_CHECK_URL');
    $encodeData = passport_encrypt(json_encode($themeData), constant('THEME_CHECK_KEY'));
    $postData = array(
        'time' => time(),
        'param' => $encodeData
    );
    $result = send_post($requestUrl, $postData);
    $resultArray = json_decode(passport_decrypt($result, constant('THEME_CHECK_KEY')));
    $canUse = $resultArray->canUse;
    $userType = $resultArray->userType;
    $leftDays = $resultArray->leftDays;
    $resultText = "欢迎使用 $themename 主题！";
    if ($canUse != '1') {
        die('<h2>您的授权服务期限已到，请至 -- <a href = "https://www.waitig.com" title = "' . $themename . '官网">' . $themename . '官网</a> -- 获取帮助，或联系QQ：504508065！</h2>');
    } else {
        $userTypeText = '';
        if ($userType != '1') {
            $userTypeText = '免费';
        } else {
            $userTypeText = '<span style = "color:red">VIP</span>';
        }
        $resultText .= "您现在是 $userTypeText 用户，剩余服务期限为： $leftDays 天，感谢您的支持！";
        return $resultText;
    }

}