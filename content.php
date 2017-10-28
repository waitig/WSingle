<?php
/**
 * File: content.php.
 * User: LiuS
 * Date: 2017/2/21
 * Time: 15:10
 * Index:http://www.waitig.com
 * Theme:WBetter Theme
 */
session_start();
$cat_id = $_SESSION['cat_id'];
$thiscat = $_SESSION['thiscat'];
/*$right_cat_id = 1;
if (is_category()) {
    $cat_id = get_cat_ID(single_cat_title('', false));
} elseif (is_home()) {
    $cat_id = waitig_gopt('index_cat_id');
} elseif (is_single()) {
    $categorys = get_the_category();
    $category = $categorys[0];
    $cat_id = get_category_root_id($category->term_id);
}*/
//$thiscat = get_category($cat_id);
$catUrl = get_category_link($thiscat->term_id);
$cats_id_arr = get_term_children($cat_id, 'category');
$themeUrl = get_template_directory_uri();
$new_list_num = waitig_gopt('new_list_num');
$blogUrl = get_bloginfo('url');
$blogName = get_bloginfo('name');
$waitig_ad_chapter_top='';
if(wp_is_mobile()){
    $waitig_ad_chapter_top =  waitig_gopt('waitig_ad_chapter_top-wap');
}
else{
    $waitig_ad_chapter_top =  waitig_gopt('waitig_ad_chapter_top-PC');
}
$waitig_ad_chapter_bottom='';
if(wp_is_mobile()){
    $waitig_ad_chapter_bottom =  waitig_gopt('waitig_ad_chapter_bottom-wap');
}
else{
    $waitig_ad_chapter_bottom =  waitig_gopt('waitig_ad_chapter_bottom-PC');
}
?>
<?php if (is_category()) { ?>
    <div class="container crumbs">
        <div class="fl"><span>当前位置：</span>
            <a href="<?= $blogUrl ?>" title="<?= $blogName ?>"><?= $blogName ?></a> &gt;
            <a href="<?= $catUrl ?>" title="<?= $thiscat->name ?>"><?= $thiscat->name ?>全文阅读</a>
        </div>
    </div>
<?php } ?>
    <div class="clear"></div>
    <div class="container">
        <div class="inner">
            <div class="bookinfo">
                <div class="btitle">
                    <h1><a href="<?= $catUrl ?>" target="_blank"><?= $thiscat->name ?></a></h1>
                    <em>作者：<?= waitig_gopt("cat_author_" . $thiscat->term_id) ?></em>
                    <div class="hidden-xs hidden-sm hidden-md">
                        <style>
                            #share {
                                float: right;
                            }

                            #share a {
                                width: 173px;
                                height: 50px;
                            }

                            #share a.bds_bdhome {
                                background: url('<?= $themeUrl ?>/img/baiduindex.png') no-repeat;
                            }
                        </style>
                        <div id="share">
                            <div class="bdsharebuttonbox"><a href="#" class="bds_bdhome" data-cmd="bdhome"
                                                             title="分享到百度新首页"></a></div>
                            <script>window._bd_share_config = {
                                    "common": {
                                        "bdSnsKey": {},
                                        "bdText": "",
                                        "bdMini": "2",
                                        "bdMiniList": false,
                                        "bdPic": "",
                                        "bdStyle": "0",
                                        "bdSize": "16"
                                    }, "share": {}
                                };
                                with (document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];</script>
                        </div>
                    </div>
                    <p class="stats">
								<span class="fl"><b>最新章节：</b><?php query_posts("posts_per_page=1&cat=" . $cat_id) ?>
                                    <?php while (have_posts()) :
                                    the_post(); ?>
                                    <a href="<?php the_permalink() ?>" target="_blank" title="<?php the_title(); ?>">
                            <?php the_title(); ?>
                    </a></span></p>
                    <div class="status"><font color="#999999">状态：</font>连载中&nbsp;&nbsp;&nbsp;<font
                                color="#999999">更新时间：</font><?php echo the_time('Y年m月d日 H:i'); ?></div>
                    <?php endwhile;
                    wp_reset_query(); ?>
                    <div class="clear"></div>
                    <div class="intro">
                        <?php if (waitig_gopt("cat_image_" . $thiscat->term_id)) { ?>
                            <p class="img-p">
                                <a class="img-a" href="<?= $catUrl ?>">
                                    <img class="img-img" alt="<?= $thiscat->name ?>" src="<?= waitig_gopt("cat_image_" . $thiscat->term_id) ?>">
                                </a>
                            </p>
                        <?php } ?>
                        <span class="intro-p">内容简介：<?= wpautop($thiscat->description) ?><div class="clear"></div></span>
                    </div>
                    <div class="clear"></div>
                    <?php if(waitig_gopt('waitig_tui')){?>
                        <div class="tuijian">
                            重磅推荐：
                            <?= waitig_gopt('waitig_tui') ?>
                        </div>
                    <?php } ?>
                </div>
<?php if (waitig_gopt("cat_other_novel_" . $cat_id)) { ?>
    <dl class="chapterlist">
        <!--最新列表-->
        <dt class="title"><?= waitig_gopt("cat_author_" . $cat_id) ?> 大神的其他作品</dt>
        <?=waitig_gopt("cat_other_novel_" . $cat_id)?>
    </dl>
    <?php } ?>
                <?= $waitig_ad_chapter_top ?>
                <dl class="chapterlist">
                    <!--最新列表-->
                    <dt class="title"><?= $thiscat->name ?> 最新章节列表</dt>
                    <?php
                    $args = array(
                        'numberposts' => $new_list_num,
                        'offset' => 0,
                        'category' => $thiscat->term_id,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'post_status' => 'publish');
                    $postList = get_posts($args);
                    foreach ($postList as $post) {
                        $postUrl = get_permalink($post->ID);
                        $postTitle = $post->post_title;
                        echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                    }
                    /**判断是否有自定义章节
                     * 如果有，则优先显示自定义章节
                     * 如果没有，则显示正文章节
                     * 如果有子分类，但没有放入子分类的文章，则显示在最后的正文章节中
                     */
                    if (count($cats_id_arr) != 0) {
                        foreach ($cats_id_arr as $childCatId) {
                            $childCat = get_category($childCatId);
                            echo "<dt class=\"title\">$childCat->name</dt>";
                            $args = array(
                                'numberposts' => -1,
                                'offset' => 0,
                                'category' => $childCatId,
                                'orderby' => 'post_date',
                                'order' => 'ASC',
                                'post_status' => 'publish');
                            $postList = get_posts($args);
                            foreach ($postList as $post) {
                                $postUrl = get_permalink($post->ID);
                                $postTitle = $post->post_title;
                                echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                            }
                        }

                    } else {
                        $args = array(
                            'numberposts' => -1,
                            'offset' => 0,
                            'category' => $thiscat->term_id,
                            'orderby' => 'post_date',
                            'order' => 'ASC',
                            'post_status' => 'publish');
                        $postList = get_posts($args);
                        echo '<dt class="title">正文</dt>';
                        foreach ($postList as $post) {
                            $postUrl = get_permalink($post->ID);
                            $postTitle = $post->post_title;
                            echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                        }
                    }
                    ?>
                </dl>
                <?= $waitig_ad_chapter_bottom ?>
            </div>
        </div>
    </div>
<?php
if (waitig_gopt('waitig_popcat_on')) {
    require_once 'popcate.php';
}
if (waitig_gopt('waitig_flink')) {
    require_once 'flink.php';
}
