<?php
/**
 * Created by PhpStorm.
 * User: lius
 * Date: 2017/2/20
 * Time: 19:49
 */
$cat_id = 1;
$right_cat_id = 1;
$blogUrl = get_bloginfo('url');
$blogName = get_bloginfo('name');
if(is_page()){
    $cat_id = 1;
}
elseif (is_category()) {
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
$catAuth = waitig_gopt("cat_author_" . $thiscat->term_id);
/**
 * 标题
 */
$title = '';
if(is_page()){
    $title = get_the_title();
}
elseif (is_home()) {
    $title = waitig_gopt('waitig_title');
} else {
    if (is_single()) {
        //文章页标题
        $title = waitig_gopt('waitig_post_seo_title');
        $postTitle = get_the_title();
        $title = str_replace('{{blog_name}}',$blogName,$title);
        $title = str_replace('{{cat_name}}',$thiscat->name,$title);
        $title = str_replace('{{cat_auth}}',$catAuth,$title);
        $title = str_replace('{{post_title}}',$postTitle,$title);
        //$title = $thiscat->name . '-' . the_title('', '', false);;
    } else {
        //小说页标题
        $title = waitig_gopt('waitig_novel_seo_title');
        $title = str_replace('{{blog_name}}',$blogName,$title);
        $title = str_replace('{{cat_name}}',$thiscat->name,$title);
        $title = str_replace('{{cat_auth}}',$catAuth,$title);
    }
    //$title .= '_' . $thiscat->name . '最新章节_' . $thiscat->name . 'TXT下载_' . waitig_gopt("cat_author_" . $thiscat->term_id) . '新书全文免费阅读_' . get_option('blogname');
}
/**
 * 关键词
 */
$keyWords = '';
if (is_home()||is_page()) {
    $keyWords = waitig_gopt('waitig_keywords');
} elseif (is_category()) {
    $keyWords = waitig_gopt('waitig_novel_seo_keywords');
    $keyWords = str_replace('{{blog_name}}',$blogName,$keyWords);
    $keyWords = str_replace('{{cat_name}}',$thiscat->name,$keyWords);
    $keyWords = str_replace('{{cat_auth}}',$catAuth,$keyWords);
    //$keyWords = $thiscat->name . ',' . $thiscat->name . '吧,' . waitig_gopt("cat_author_" . $thiscat->term_id) . ',' . $thiscat->name . '小说,' . $thiscat->name . '最新章节,' . $thiscat->name . '无弹窗,' . $thiscat->name . '全文阅读,' . $thiscat->name . '免费阅读,' . $thiscat->name . 'TXT下载';
}
else{
    $keyWords = waitig_gopt('waitig_post_seo_keywords');
    $postTitle = get_the_title();
    $keyWords = str_replace('{{blog_name}}',$blogName,$keyWords);
    $keyWords = str_replace('{{cat_name}}',$thiscat->name,$keyWords);
    $keyWords = str_replace('{{cat_auth}}',$catAuth,$keyWords);
    $keyWords = str_replace('{{post_title}}',$postTitle,$keyWords);
}
/**
 * 描述
 */
$description = '';
if (is_home()||is_page()) {
    $description = waitig_gopt('waitig_description');
} elseif(is_category()) {
    $description = waitig_gopt('waitig_novel_seo_desc');
    $description = str_replace('{{blog_name}}',$blogName,$description);
    $description = str_replace('{{cat_name}}',$thiscat->name,$description);
    $description = str_replace('{{cat_auth}}',$catAuth,$description);
    //$description = $thiscat->name . '是' . waitig_gopt("cat_author_" . $thiscat->term_id) . '创作的全新精彩小说，' . $thiscat->name . '最新章节来源于互联网网友,' . get_option('blogname') . '提供' . $thiscat->name . '全文在线免费阅读，及' . $thiscat->name . 'TXT下载，并且无任何弹窗广告。';
}
else{
    $description = waitig_gopt('waitig_post_seo_desc');
    $postTitle = get_the_title();
    $description = str_replace('{{blog_name}}',$blogName,$description);
    $description = str_replace('{{cat_name}}',$thiscat->name,$description);
    $description = str_replace('{{cat_auth}}',$catAuth,$description);
    $description = str_replace('{{post_title}}',$postTitle,$description);
}
/**
 * base url
 */
$baseUrl = str_replace('', '/', dirname($_SERVER['SCRIPT_NAME']));
$themeUrl = get_template_directory_uri();
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
        <?php if(!is_page()){?>
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
        wp_reset_query(); } ?>
        <link rel="stylesheet" href="<?= $themeUrl ?>/style.css?ver=<?=constant('THEMEVERSION')?>" type="text/css" media="screen">
        <link rel="stylesheet" media="screen and (max-width:600px)" href="<?= $themeUrl ?>/css/mobile.css"
              type="text/css">
        <script type="text/javascript" src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
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
                <p class="not"><a style="color:red" href="<?= $blogUrl ?>"><?= $blogName ?></a> 全新改版，无弹窗，最值得书友收藏的小说阅读网！</p>
                <p class="qq"><a target="_blank" rel="nofollow" href="<?= $qq_qun_link ?>">
                        <img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="加入QQ群" title="点击加入QQ群">
                    </a>
                </p>
            </div>
        </div>
    </div>
    <?php
}
