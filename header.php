<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/2/20
 * Time: 19:49
 */
$cat_id = 1;
$right_cat_id = 1;
if (is_category()) {
    $cat_id = get_cat_ID(single_cat_title('', false));
} elseif (is_home()) {
    $cat_id = waitig_gopt('index_cat_id');
} elseif (is_single()) {
    $categorys = get_the_category();
    $thiscat = $categorys[0];
    $cat_id = $thiscat->term_id;
}
$thiscat = get_category($cat_id);
/**
 * 标题
 */
$title = '';
if (is_home()) {
    $title = waitig_gopt('waitig_title');
} else {
    if (is_single()) {
        $title = $thiscat->name . '-' . the_title('', '', false);;
    } else {
        $title = $thiscat->name;
    }
    $title .= '_' . $thiscat->name . '最新章节_' . $thiscat->name . 'TXT下载_' . waitig_gopt("cat_author_" . $thiscat->term_id) . '新书全文免费阅读_' . get_option('blogname');
}
/**
 * 关键词
 */
$keyWords = '';
if (is_home()) {
    $keyWords = waitig_gopt('waitig_keywords');
} else {
    $keyWords = $thiscat->name . ',' . $thiscat->name . '吧,' . waitig_gopt("cat_author_" . $thiscat->term_id) . ',' . $thiscat->name . '小说,' . $thiscat->name . '最新章节,' . $thiscat->name . '无弹窗,' . $thiscat->name . '全文阅读,' . $thiscat->name . '免费阅读,' . $thiscat->name . 'TXT下载';
}
/**
 * 描述
 */
$description = '';
if (is_home()) {
    $description = waitig_gopt('waitig_description');
} else {
    $description = $thiscat->name . '是' . waitig_gopt("cat_author_" . $thiscat->term_id) . '创作的全新精彩小说，' . $thiscat->name . '最新章节来源于互联网网友,' . get_option('blogname') . '提供' . $thiscat->name . '全文在线免费阅读，及' . $thiscat->name . 'TXT下载，并且无任何弹窗广告。';
}
/**
 * base url
 */
$baseUrl = str_replace('', '/', dirname($_SERVER['SCRIPT_NAME']));
$themeUrl = get_template_directory_uri();
?>
<!DOCTYPE html>
<html class="no-js" lang="zh-CN">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?= $title ?></title>
    <meta name="keywords" content="<?= $keyWords ?>">
    <meta name="description" content="<?= $description ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="applicable-device" content="pc,mobile">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta http-equiv="Cache-Control" content="no-transform">
    <link rel="shortcut icon" href="<?= $themeUrl ?>/img/favicon.ico">
    <meta property="og:type" content="novel"/>
    <meta property="og:title" content="<?= $thiscat->name ?>"/>
    <meta property="og:description" content="<?= $thiscat->description ?>"/>
    <meta property="og:image" content="<?= waitig_gopt("cat_image_" . $thiscat->term_id) ?>"/>
    <meta property="og:url" content="<?= get_category_link($thiscat->term_id) ?>"/>
    <meta property="og:novel:status" content="连载"/>
    <meta property="og:novel:author" content="<?= waitig_gopt("cat_author_" . $thiscat->term_id) ?>"/>
    <meta property="og:novel:book_name" content="<?= $thiscat->name ?>"/>
    <meta property="og:novel:read_url" content="<?= get_category_link($thiscat->term_id) ?>"/>
    <?php query_posts("posts_per_page=1&cat=" . $thiscat->term_id) ?>
    <?php while (have_posts()) : the_post(); ?>
    <meta property="og:novel:update_time" content="<?= the_time('Y-m-d H:i') ?>"/>
    <meta property="og:novel:latest_chapter_name" content="<?= get_the_title() ?>"/>
    <meta property="og:novel:latest_chapter_url" content="<?= get_the_permalink() ?>"/>
    <?php endwhile;
    wp_reset_query(); ?>
    <link rel="stylesheet" href="<?= $themeUrl ?>/style.css" type="text/css" media="screen">
    <link rel="stylesheet" media="screen and (max-width:600px)" href="<?= $themeUrl ?>/mobile.css" type="text/css">
    <script type="text/javascript" src="<?= $themeUrl ?>/user.js"></script>
    <script type="text/javascript" src="<?= $themeUrl ?>/nono.js"></script>
    <script type="text/javascript" src="<?= $themeUrl ?>/share.js"></script>
    <link rel="stylesheet" href="<?= $themeUrl ?>/share_style0_16.css">
</head>
<body style="zoom: 1;">
<header id="header">
    <div id="topbar">
        <div class="hd">
            <div class="share">
                <em>您可以按"CRTL+D"将"<?= bloginfo("name") ?>"加入收藏夹！或分享到：</em>
                <script>share();</script>
                <div class="bdsharebuttonbox bdshare-button-style0-16" style="margin-top:3px;"
                     data-bd-bind="1504609331595"></div>
                <script>window._bd_share_config = {
                        "common": {
                            "bdSnsKey": {},
                            "bdText": "",
                            "bdMini": "1",
                            "bdMiniList": false,
                            "bdPic": "",
                            "bdStyle": "0",
                            "bdSize": "16"
                        }, "share": {}
                    };
                    with (document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];</script>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</header>