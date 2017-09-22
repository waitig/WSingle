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
    //var_dump($categorys);
    $cat = $categorys[0];
    $thiscat = get_root_category($cat);
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
$blogUrl = get_bloginfo('url');
$blogName = get_bloginfo('name');
$qq_qun_link = waitig_gopt('qq_qun_link');
$waitig_head_code = waitig_gopt('waitig_head_code');
session_start();
$_SESSION['cat_id'] = $cat_id;
$_SESSION['thiscat'] = $thiscat;
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
        <link rel="stylesheet" href="<?= $themeUrl ?>/style.css?ver=1.05" type="text/css" media="screen">
        <link rel="stylesheet" media="screen and (max-width:600px)" href="<?= $themeUrl ?>/css/mobile.css"
              type="text/css">
        <script type="text/javascript" src="<?= $themeUrl ?>/js/waitig.js"></script>
        <?= $waitig_head_code ?>
    </head>
<body>
    <!-- Fixed navbar -->
    <header id="header">
        <div id="topbar">
            <div class="hd">
                <div class="share">
                    <em>您可以按"CRTL+D"将"<?= $blogName ?>"加入收藏夹！或分享到：</em>
                    <script>share();</script>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </header>
<?php if ($qq_qun_link) { ?>
    <div class="container">
        <div class="inner">
            <div class="details">
                <p class="not"><font color="red"><?= $blogName ?>(<?= $blogUrl ?>)</font> 全新改版，无弹窗，最值得书友收藏的小说阅读网！</p>
                <p class="qq"><a target="_blank" rel="nofollow" href="<?= $qq_qun_link ?>">
                        <img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="加入QQ群" title="点击加入QQ群">
                    </a>
                </p>
            </div>
        </div>
    </div>
    <?php
}
