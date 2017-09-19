<?php
/**
 * File: content.php.
 * User: LiuS
 * Date: 2017/2/21
 * Time: 15:10
 * Index:http://www.waitig.com
 * Theme:WBetter Theme
 */
$cat_id = 1;
$right_cat_id = 1;
if (is_category()) {
    $cat_id = get_cat_ID(single_cat_title('', false));
} elseif (is_home()) {
    $cat_id = waitig_gopt('index_cat_id');
} elseif (is_single()) {
    $categorys = get_the_category();
    $category = $categorys[0];
    $cat_id = $category->term_id;
}
$thiscat = get_category($cat_id);
$catUrl = get_category_link($thiscat->term_id);
$right_cat_id = waitig_gopt('right_cat_id');
$cats_id_arr = get_term_children($cat_id, 'category');
$themeUrl = get_template_directory_uri();
$new_list_num = waitig_gopt('new_list_num');
?>
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
                    <p style="float: left;width: 100px;height: 133px;margin: 15px 15px 0px 15px;border: 1px solid #ccc;padding: 5px;">
                        <a style="width:100px; height:133px;" href="<?= $catUrl ?>"><img
                                    style="width:100px; height:133px;" alt="<?= $thiscat->name ?>"
                                    src="<?= waitig_gopt("cat_image_" . $thiscat->term_id) ?>"></a></p>
                    <p style=" margin-top:20px;">内容简介：<?= $thiscat->description ?></p>
                </div>
                <div class="clear"></div>
                <div class="tuijian">
                    重磅推荐：
                    <?= waitig_gopt('waitig_tui') ?>
                </div>
            </div>
            <script>chapter_top();</script>
            <dl class="chapterlist">
                <!--最新列表-->
                <dt class="title"><?= $thiscat->name ?> 最新章节列表</dt>
                <?php
                query_posts("posts_per_page=".$new_list_num."&cat=" . $thiscat->term_id . "&order=ASC");
                while (have_posts()) :
                    the_post();
                    $postUrl = get_the_permalink();
                    $postTitle = get_the_title();
                    echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                endwhile;
                wp_reset_query();
                ?>
                <?php if (count($cats_id_arr) == 0) {
                    echo '<dt class="title">正文</dt>';
                    query_posts("posts_per_page=-1&cat=" . $thiscat->term_id . "&order=ASC");
                    while (have_posts()) :
                        the_post();
                        $postUrl = get_the_permalink();
                        $postTitle = get_the_title();
                        echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                    endwhile;
                    wp_reset_query();
                } else {
                    foreach ($cats_id_arr as $childCatId) {
                        $childCat = get_category($childCatId);
                        echo "<dt class=\"title\">$childCat->name</dt>";
                        query_posts("posts_per_page=-1&cat=" . $childCatId . "&order=ASC");
                        while (have_posts()) :
                            the_post();
                            $postUrl = get_the_permalink();
                            $postTitle = get_the_title();
                            echo "<dd><a href=\"$postUrl\" title=\"$postTitle\">$postTitle</a></dd>";
                        endwhile;
                        wp_reset_query();
                    }
                } ?>
            </dl>
            <script>chapter_bottom();</script>
        </div>
    </div>
</div>
<?php
if(waitig_gopt('waitig_popcat_on')){
    require_once 'popcate.php';
}
